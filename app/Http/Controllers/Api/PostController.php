<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * 📡 Listar posts
     */
    public function index()
    {
        return response()->json(
            Post::with(['user', 'media'])
                ->latest()
                ->get()
        );
    }

    /**
     * 📤 Crear post
     */
    public function store(Request $request)
    {
        $request->validate([
            'description' => 'nullable|string|max:1000',
            'game_id' => 'nullable|exists:games,id',
            'image' => 'nullable|image|max:5120',
        ]);

        // 🧠 Crear post base
        $post = Post::create([
            'user_id' => $request->user()->id,
            'game_id' => $request->game_id,
            'description' => $request->description,
        ]);

        // 📦 Crear media solo si archivo válido
        if ($request->hasFile('image')) {

            $file = $request->file('image');

            if ($file && $file->isValid()) {

                $path = $file->store('posts', 'public');

                if ($path) {
                    $post->media()->create([
                        'url' => $path,
                        'type' => 'image',
                    ]);
                }
            }
        }

        return response()->json([
            'message' => 'Post creado correctamente',
            'post' => $post->load(['user', 'media'])
        ], 201);
    }

    /**
     * 📄 Ver post
     */
    public function show(string $id)
    {
        return response()->json(
            Post::with(['user', 'media'])->findOrFail($id)
        );
    }

    /**
     * ✏️ Actualizar post
     */
    public function update(Request $request, string $id)
    {
        $post = Post::findOrFail($id);

        if ($post->user_id != $request->user()->id) {
            return response()->json([
                'message' => 'No autorizado'
            ], 403);
        }

        $request->validate([
            'description' => 'required|string|max:1000'
        ]);

        $post->update([
            'description' => $request->description
        ]);

        return response()->json([
            'message' => 'Publicación actualizada',
            'post' => $post->load(['user', 'media'])
        ]);
    }

    /**
     * 🗑️ Eliminar post
     */
    public function destroy(Request $request, string $id)
    {
        $post = Post::with('media')->findOrFail($id);

        if ($post->user_id != $request->user()->id) {
            return response()->json([
                'message' => 'No autorizado'
            ], 403);
        }

        // 🧹 borrar archivos físicos
        foreach ($post->media as $media) {
            if (!empty($media->url)) {
                Storage::disk('public')->delete($media->url);
            }
        }

        // 🗑️ borrar registros
        $post->media()->delete();
        $post->delete();

        return response()->json([
            'message' => 'Publicación eliminada correctamente'
        ]);
    }
}