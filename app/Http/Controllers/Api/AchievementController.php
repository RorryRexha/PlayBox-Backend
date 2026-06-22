<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Achievement;

class AchievementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    return response()->json(
        Achievement::with('user')
            ->latest()
            ->get()
    );
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'image' => 'nullable|string'
    ]);

    $achievement = Achievement::create([
        'user_id' => $request->user_id,
        'title' => $request->title,
        'description' => $request->description,
        'image' => $request->image
    ]);

    return response()->json([
        'message' => 'Logro agregado correctamente',
        'achievement' => $achievement
    ],201);
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
{
    return response()->json(
        Achievement::with('user')->findOrFail($id)
    );
}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
{
    $achievement = Achievement::findOrFail($id);

    $achievement->update($request->only([
        'title',
        'description',
        'image'
    ]));

    return response()->json([
        'message' => 'Logro actualizado',
        'achievement' => $achievement
    ]);
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
{
    Achievement::findOrFail($id)->delete();

    return response()->json([
        'message' => 'Logro eliminado'
    ]);
}
}
