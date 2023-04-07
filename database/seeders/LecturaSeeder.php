<?php

namespace Database\Seeders;

use App\Models\Lector;
use App\Models\Lectura;
use App\Models\Libreria;
use App\Models\Libro;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LecturaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lectores = Lector::all();
        $libro = Libro::find(1);
        $libreria = Libreria::find(1);

        foreach ($lectores as $lector) {
            $lectura = new Lectura;
            $lectura->fecha_inicio = '2022-01-01';
            $lectura->estado = 'Pendiente';
            $lectura->libreria_id = $libreria->id;
            $lectura->lector_id = $lector->id;
            $lectura->libro_id = $libro->id;
            $lectura->save();
        }
    }
}
