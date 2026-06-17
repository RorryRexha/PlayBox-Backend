<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();

            // Usuario que creó la publicación
            $table->foreignId('user_id')
                  ->constrained()
                  ->onDelete('cascade');

            // Juego al que pertenece la publicación
            $table->foreignId('game_id')
                  ->constrained()
                  ->onDelete('cascade');

            // Texto de la publicación
            $table->text('description');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};