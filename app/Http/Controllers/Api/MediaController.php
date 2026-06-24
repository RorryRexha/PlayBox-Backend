<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    /**
     * 📡 Listar media
     */
    public function index()
    {
        return response()->json(
            Media::with('post')->latest()->get()
        );
    }

    /**
     * 📤 Crear media (manual si lo necesitas)
     * 👉 Opcional, porque normalmente lo crea PostController
     */
    class MediaController extends Controller
{
    public function index()
    {
        return response()->json(
            Media::with('post')->latest()->get()
        );
    }

    public function show(string $id)
    {
        return response()->json(
            Media::with('post')->findOrFail($id)
        );
    }

    public function destroy(string $id)
    {
        $media = Media::findOrFail($id);

        $media->delete();

        return response()->json([
            'message' => 'Archivo eliminado correctamente'
        ]);
    }
}
    /**
     * 📄 Ver media
     */
    public function show(string $id)
    {
        return response()->json(
            Media::with('post')->findOrFail($id)
        );
    }

    /**
     * 🗑️ Eliminar media
     */
    public function destroy(string $id)
    {
        $media = Media::findOrFail($id);

        // ❗ si quieres borrar archivo físico en storage:
        if ($media->url) {
            Storage::disk('public')->delete($media->url);
        }

        $media->delete();

        return response()->json([
            'message' => 'Archivo eliminado correctamente'
        ]);
    }
}