<?php

namespace Database\Seeders;

use App\Models\Lector;
use App\Models\Libreria;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LibreriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // Obtenemos el primer lector
         $lector = Lector::first();

         // Creamos tres librerías con diferentes nombres y las asociamos al lector
         Libreria::create([
             'nombre' => 'Leído',
             'lector_id' => $lector->id
         ]);
 
         Libreria::create([
             'nombre' => 'Leyendo',
             'lector_id' => $lector->id
         ]);
 
         Libreria::create([
             'nombre' => 'Quiero leer',
             'lector_id' => $lector->id
         ]);
     
    }
}
