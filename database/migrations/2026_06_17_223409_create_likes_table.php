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
        Schema::create('likes', function (Blueprint $table) {
            $table->id();

            // Usuario que dio like
            $table->foreignId('user_id')
                  ->constrained()
                  ->onDelete('cascade');

            // Publicación a la que se dio like
            $table->foreignId('post_id')
                  ->constrained()
                  ->onDelete('cascade');

            // Evita likes duplicados del mismo usuario a la misma publicación
            $table->unique(['user_id', 'post_id']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('likes');
    }
};