<?php

namespace Database\Seeders;

use App\Models\Lector;
use App\Models\Libro;
use App\Models\Valoracion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ValoracionesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $libros = Libro::all();
        $lectores = Lector::all();

        foreach ($libros as $libro) {
            foreach ($lectores as $lector) {
                $valoracion = new Valoracion();
                $valoracion->puntuacion = rand(1, 5);
                $valoracion->comentario = "Comentario de prueba";
                $valoracion->libro_id = $libro->id;
                $valoracion->lector_id = $lector->id;
                $valoracion->save();
            }
        }
    }
}
