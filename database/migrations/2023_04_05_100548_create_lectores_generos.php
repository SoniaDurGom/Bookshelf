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
        Schema::create('lectores_generos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lector_id')->constrained('lectores');
            $table->foreignId('genero_id')->constrained('generos');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lectores_generos');
    }
};
