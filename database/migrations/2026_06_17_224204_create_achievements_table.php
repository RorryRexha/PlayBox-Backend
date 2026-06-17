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
        Schema::create('achievements', function (Blueprint $table) {
            $table->id();

            // Usuario propietario del logro
            $table->foreignId('user_id')
                  ->constrained()
                  ->onDelete('cascade');

            // Nombre del logro
            $table->string('title');

            // Descripción del logro
            $table->text('description')->nullable();

            // Imagen o icono del logro
            $table->string('image')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('achievements');
    }
};