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
        // Schema::create('autores', function (Blueprint $table) {
        //     $table->id();
        //     $table->unsignedBigInteger('id_perfil');
        //     $table->foreign('id_perfil')->references('id')->on('perfiles')->onDelete('cascade'); //onDelete borra las lineas de las tablas que tengan esta referencia cuando se borre
        //     $table->text('biografia')->nullable();
        //     $table->timestamps();
        // });
        Schema::create('autores', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('perfil_id');
            $table->text('biografia')->nullable();
            $table->boolean('aprobado')->default(false);
            $table->timestamps();
        
            $table->foreign('perfil_id')->references('id')->on('perfiles');
        });
        
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('autores');
    }
};
