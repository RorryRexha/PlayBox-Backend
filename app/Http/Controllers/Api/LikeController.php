<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Like;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    /**
     * Dar like a un post
     */
    public function like(Request $request, $postId)
    {
        $user = $request->user();

        // Validar que el post exista
        $post = Post::find($postId);

        if (!$post) {
            return response()->json([
                'message' => 'Post no encontrado'
            ], 404);
        }

        // Evitar duplicados (aunque ya lo protege la BD)
        $like = Like::where('user_id', $user->id)
                    ->where('post_id', $postId)
                    ->first();

        if ($like) {
            return response()->json([
                'message' => 'Ya diste like a este post'
            ], 409);
        }

        // Crear like
        Like::create([
            'user_id' => $user->id,
            'post_id' => $postId
        ]);

        return response()->json([
            'message' => 'Like agregado correctamente',
            'liked' => true,
            'likes_count' => $post->likes()->count()
        ], 201);
    }

    /**
     * Quitar like a un post
     */
    public function unlike(Request $request, $postId)
    {
        $user = $request->user();

        $like = Like::where('user_id', $user->id)
                    ->where('post_id', $postId)
                    ->first();

        if (!$like) {
            return response()->json([
                'message' => 'No has dado like a este post'
            ], 404);
        }

        $like->delete();

        $post = Post::find($postId);

        return response()->json([
            'message' => 'Like eliminado correctamente',
            'liked' => false,
            'likes_count' => $post ? $post->likes()->count() : 0
        ]);
    }

    /**
     * Toggle like (RECOMENDADO para frontend)
     * Si existe like → lo quita
     * Si no existe → lo crea
     */
    public function toggle(Request $request, $postId)
    {
        $user = $request->user();

        $post = Post::find($postId);

        if (!$post) {
            return response()->json([
                'message' => 'Post no encontrado'
            ], 404);
        }

        $like = Like::where('user_id', $user->id)
                    ->where('post_id', $postId)
                    ->first();

        if ($like) {
            $like->delete();
            $liked = false;
            $message = 'Like removido';
        } else {
            Like::create([
                'user_id' => $user->id,
                'post_id' => $postId
            ]);
            $liked = true;
            $message = 'Like agregado';
        }

        return response()->json([
            'message' => $message,
            'liked' => $liked,
            'likes_count' => $post->likes()->count()
        ]);
    }

    /**
     * Obtener conteo de likes de un post
     */
    public function count($postId)
    {
        $post = Post::find($postId);

        if (!$post) {
            return response()->json([
                'message' => 'Post no encontrado'
            ], 404);
        }

        return response()->json([
            'post_id' => $postId,
            'likes_count' => $post->likes()->count()
        ]);
    }

    /**
     * Verificar si el usuario dio like
     */
    public function check(Request $request, $postId)
    {
        $user = $request->user();

        $liked = Like::where('user_id', $user->id)
                    ->where('post_id', $postId)
                    ->exists();

        return response()->json([
            'post_id' => $postId,
            'liked' => $liked
        ]);
    }
}