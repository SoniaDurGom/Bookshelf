<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Autor;
use App\Models\Autor_Sin_Cuenta;
use App\Models\Libro;

class Autores_librosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $libros = Libro::all();

        $autores = Autor_Sin_Cuenta::all();

        foreach ($libros as $libro) {
            $num_autores = rand(1, count($autores));

            // seleccionamos aleatoriamente el número de autores que tendrá el libro
            $autores_seleccionados = $autores->random($num_autores);

            // agregamos los autores al libro
            foreach ($autores_seleccionados as $autor) {
                $libro->autorSinCuenta()->attach($autor->id);
            }
        }
    }
}