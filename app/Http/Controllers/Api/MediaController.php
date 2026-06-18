<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    /**
     * Mostrar todos los archivos
     */
    public function index()
    {
        return response()->json(
            Media::with('post')->latest()->get()
        );
    }

    /**
     * Registrar archivo
     */
    public function store(Request $request)
    {
        $request->validate([
            'post_id' => 'required|exists:posts,id',
            'url' => 'required|string',
            'type' => 'required|in:image,video'
        ]);

        $media = Media::create([
            'post_id' => $request->post_id,
            'url' => $request->url,
            'type' => $request->type
        ]);

        return response()->json([
            'message' => 'Archivo agregado correctamente',
            'media' => $media
        ],201);
    }

    /**
     * Mostrar archivo
     */
    public function show(string $id)
    {
        return response()->json(
            Media::with('post')->findOrFail($id)
        );
    }

    /**
     * Eliminar archivo
     */
    public function destroy(string $id)
    {
        Media::findOrFail($id)->delete();

        return response()->json([
            'message' => 'Archivo eliminado'
        ]);
    }
}