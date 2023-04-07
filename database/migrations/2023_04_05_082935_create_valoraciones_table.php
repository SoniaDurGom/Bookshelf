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
        Schema::create('valoraciones', function (Blueprint $table) {
            $table->id();
            $table->integer('puntuacion');
            $table->text('comentario')->nullable();
            $table->foreignId('libro_id')->constrained('libros')->onDelete('cascade');;
            $table->foreignId('lector_id')->constrained('lectores')->onDelete('cascade');;
            // $table->unique(['libro_id', 'lector_id']); //Solo una Valoracion por par libro-usuario
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('valoraciones');
    }
};
