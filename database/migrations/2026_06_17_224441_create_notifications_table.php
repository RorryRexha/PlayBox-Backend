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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();

            // Usuario que recibe la notificación
            $table->foreignId('user_id')
                  ->constrained()
                  ->onDelete('cascade');

            // Usuario que originó la acción
            $table->foreignId('sender_id')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            // Tipo de notificación
            $table->enum('type', [
                'like',
                'comment',
                'follow',
                'achievement',
                'system'
            ]);

            // Mensaje de la notificación
            $table->string('message');

            // Indica si fue leída
            $table->boolean('is_read')
                  ->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};