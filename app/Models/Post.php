<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'game_id',
        'description',
    ];

    // =========================
    // 👤 RELACIÓN USUARIO
    // =========================
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // =========================
    // 🎮 RELACIÓN JUEGO
    // =========================
    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    // =========================
    // 💬 COMENTARIOS
    // =========================
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // =========================
    // ❤️ LIKES
    // =========================
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    // =========================
    // 📦 MEDIA (IMÁGENES / VIDEOS)
    // =========================
    public function media()
    {
        return $this->hasMany(Media::class);
    }
}