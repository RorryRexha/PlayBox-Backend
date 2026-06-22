<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    return response()->json(
        Notification::with('sender')
            ->where('user_id', auth()->id())
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
        'sender_id' => 'nullable|exists:users,id',
        'type' => 'required|in:like,comment,follow,achievement,system',
        'message' => 'required|string|max:255'
    ]);

    $notification = Notification::create([
        'user_id' => $request->user_id,
        'sender_id' => $request->sender_id,
        'type' => $request->type,
        'message' => $request->message
    ]);

    return response()->json([
        'message' => 'Notificación creada correctamente',
        'notification' => $notification
    ], 201);
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
{
    return response()->json(
        Notification::with('sender')->findOrFail($id)
    );
}
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
{
    $notification = Notification::findOrFail($id);

    $notification->update([
        'is_read' => true
    ]);

    return response()->json([
        'message' => 'Notificación actualizada',
        'notification' => $notification
    ]);
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
{
    Notification::findOrFail($id)->delete();

    return response()->json([
        'message' => 'Notificación eliminada'
    ]);
}
}
