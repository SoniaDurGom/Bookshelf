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
        Schema::create('lecturas', function (Blueprint $table) {
            $table->id();
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->integer('paginas_leidas')->nullable();
            $table->enum('estado', ['Pendiente', 'Leyendo', 'Leido']);
            //Foreings
            $table->unsignedBigInteger('libreria_id');
            $table->foreign('libreria_id')->references('id')->on('librerias')->onDelete('cascade');
            $table->unsignedBigInteger('libro_id');
            $table->foreign('libro_id')->references('id')->on('libros')->onDelete('cascade');
            $table->unsignedBigInteger('lector_id');
            $table->foreign('lector_id')->references('id')->on('lectores')->onDelete('cascade');
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lecturas');
    }
};
