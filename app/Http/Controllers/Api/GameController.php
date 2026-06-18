<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Game;
use Illuminate\Http\Request;

class GameController extends Controller
{
    /**
     * Mostrar todos los juegos
     */
    public function index()
    {
        return response()->json(
            Game::latest()->get()
        );
    }

    /**
     * Crear un juego
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'cover_image' => 'nullable|string',
            'description' => 'nullable|string'
        ]);

        $game = Game::create([
            'name' => $request->name,
            'cover_image' => $request->cover_image,
            'description' => $request->description
        ]);

        return response()->json([
            'message' => 'Juego creado correctamente',
            'game' => $game
        ], 201);
    }

    /**
     * Mostrar un juego
     */
    public function show(string $id)
    {
        $game = Game::findOrFail($id);

        return response()->json($game);
    }

    /**
     * Actualizar un juego
     */
    public function update(Request $request, string $id)
    {
        $game = Game::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'cover_image' => 'nullable|string',
            'description' => 'nullable|string'
        ]);

        $game->update([
            'name' => $request->name,
            'cover_image' => $request->cover_image,
            'description' => $request->description
        ]);

        return response()->json([
            'message' => 'Juego actualizado',
            'game' => $game
        ]);
    }

    /**
     * Eliminar un juego
     */
    public function destroy(string $id)
    {
        $game = Game::findOrFail($id);

        $game->delete();

        return response()->json([
            'message' => 'Juego eliminado'
        ]);
    }
}