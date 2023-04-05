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
          Schema::create('libros', function (Blueprint $table) {
                $table->id();
                $table->string('isbn');
                $table->string('portada');
                $table->string('titulo');
                $table->float('notamedia')->default(0);
                $table->integer('numero_valoraciones')->default(0);
                $table->date('fecha_publicacion');
                $table->integer('numero_paginas');
                $table->unsignedBigInteger('editorial_id');
                $table->foreign('editorial_id')->references('id')->on('editoriales')->onDelete('cascade');;
                $table->timestamps();
            });
            
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::table('libros', function (Blueprint $table) {
        //     $table->dropForeign(['editorial_id']);
        //     $table->dropColumn('editorial_id');
        // });

        Schema::dropIfExists('libros');
    }
};
