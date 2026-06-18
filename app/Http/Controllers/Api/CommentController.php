<?php

namespace App\Http\Controllers\Api;
use App\Models\Comment; 
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
{
    $request->validate([
        'post_id' => 'required|exists:posts,id',
        'comment' => 'required|string|max:1000'
    ]);

    $comment = Comment::create([
        'user_id' => $request->user()->id,
        'post_id' => $request->post_id,
        'comment' => $request->comment
    ]);

    return response()->json([
        'message' => 'Comentario agregado correctamente',
        'comment' => $comment
    ], 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
