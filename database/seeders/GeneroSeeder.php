<?php

namespace Database\Seeders;

use App\Models\Genero;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GeneroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        echo "Ejecutando seeder de generos...\n";
        $generos = [
            ['nombre' => 'Novela'],
            ['nombre' => 'Poesía'],
            ['nombre' => 'Ensayo'],
            // ...
        ];

        foreach ($generos as $genero) {
            // dd( $genero);
            Genero::create($genero);
        }
    }
}
