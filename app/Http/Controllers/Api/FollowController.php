<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Follower;
use App\Models\User;

class FollowController extends Controller
{
    /**
     * Seguir a un usuario
     */
    public function follow(Request $request, $userId)
    {
        $user = $request->user();

        // No puedes seguirte a ti mismo
        if ($user->id == $userId) {
            return response()->json([
                'message' => 'No puedes seguirte a ti mismo'
            ], 422);
        }

        // Verificar que el usuario exista
        $targetUser = User::find($userId);

        if (!$targetUser) {
            return response()->json([
                'message' => 'Usuario no encontrado'
            ], 404);
        }

        // Evitar duplicados
        $exists = Follower::where('follower_id', $user->id)
            ->where('following_id', $userId)
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Ya sigues a este usuario',
                'following' => true
            ], 409);
        }

        Follower::create([
            'follower_id' => $user->id,
            'following_id' => $userId
        ]);

        return response()->json([
            'message' => 'Ahora sigues a este usuario',
            'following' => true
        ], 201);
    }

    /**
     * Dejar de seguir a un usuario
     */
    public function unfollow(Request $request, $userId)
    {
        $user = $request->user();

        $follow = Follower::where('follower_id', $user->id)
            ->where('following_id', $userId)
            ->first();

        if (!$follow) {
            return response()->json([
                'message' => 'No sigues a este usuario',
                'following' => false
            ], 404);
        }

        $follow->delete();

        return response()->json([
            'message' => 'Has dejado de seguir a este usuario',
            'following' => false
        ]);
    }

    /**
     * Toggle follow (RECOMENDADO PARA FRONTEND)
     */
    public function toggle(Request $request, $userId)
    {
        $user = $request->user();

        if ($user->id == $userId) {
            return response()->json([
                'message' => 'No puedes seguirte a ti mismo'
            ], 422);
        }

        $follow = Follower::where('follower_id', $user->id)
            ->where('following_id', $userId)
            ->first();

        if ($follow) {
            $follow->delete();

            return response()->json([
                'message' => 'Has dejado de seguir',
                'following' => false
            ]);
        }

        Follower::create([
            'follower_id' => $user->id,
            'following_id' => $userId
        ]);

        return response()->json([
            'message' => 'Ahora sigues a este usuario',
            'following' => true
        ]);
    }

    /**
     * Lista de seguidores (quién me sigue)
     */
    public function followers($userId)
    {
        $user = User::find($userId);

        if (!$user) {
            return response()->json([
                'message' => 'Usuario no encontrado'
            ], 404);
        }

        $followers = Follower::where('following_id', $userId)
            ->with('follower:id,name,email')
            ->get();

        return response()->json([
            'count' => $followers->count(),
            'followers' => $followers
        ]);
    }

    /**
     * Lista de seguidos (a quién sigo)
     */
    public function following($userId)
    {
        $user = User::find($userId);

        if (!$user) {
            return response()->json([
                'message' => 'Usuario no encontrado'
            ], 404);
        }

        $following = Follower::where('follower_id', $userId)
            ->with('following:id,name,email')
            ->get();

        return response()->json([
            'count' => $following->count(),
            'following' => $following
        ]);
    }

    /**
     * Verificar si sigo a un usuario
     */
    public function check(Request $request, $userId)
    {
        $user = $request->user();

        $following = Follower::where('follower_id', $user->id)
            ->where('following_id', $userId)
            ->exists();

        return response()->json([
            'following' => $following
        ]);
    }
}