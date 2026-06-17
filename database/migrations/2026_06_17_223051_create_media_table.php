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
        Schema::create('media', function (Blueprint $table) {
            $table->id();

            // Publicación a la que pertenece el archivo
            $table->foreignId('post_id')
                  ->constrained()
                  ->onDelete('cascade');

            // URL del archivo almacenado en AWS S3
            $table->string('url');

            // Tipo de archivo: image o video
            $table->enum('type', ['image', 'video']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};