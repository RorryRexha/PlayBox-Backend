<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Mostrar todas las publicaciones
     */
    public function index()
    {
        return response()->json(
            Post::with('user')
                ->latest()
                ->get()
        );
    }

    /**
     * Crear una nueva publicación
     */
    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required|string|max:1000',
            'game_id' => 'nullable|exists:games,id'
        ]);

        $post = Post::create([
            'user_id' => $request->user()->id,
            'game_id' => $request->game_id,
            'description' => $request->description,
        ]);

        return response()->json([
            'message' => 'Publicación creada correctamente',
            'post' => $post
        ], 201);
    }

    /**
     * Mostrar una publicación
     */
    public function show(string $id)
    {
        $post = Post::with('user')->findOrFail($id);

        return response()->json($post);
    }

    /**
     * Actualizar una publicación
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
            'post' => $post
        ]);
    }

    /**
     * Eliminar una publicación
     */
    public function destroy(string $id)
    {
        $post = Post::findOrFail($id);

        if ($post->user_id != auth()->id()) {
            return response()->json([
                'message' => 'No autorizado'
            ], 403);
        }

        $post->delete();

        return response()->json([
            'message' => 'Publicación eliminada'
        ]);
    }
}