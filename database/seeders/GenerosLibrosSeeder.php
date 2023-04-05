<?php

namespace Database\Seeders;

use App\Models\Genero;
use App\Models\Libro;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GenerosLibrosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        echo "Ejecutando seeder de generos Libros...\n";
        $libros = [
            [
                'isbn' => '9788491819773',
                'genero_id' => '2',
               
            ],
            [
                'isbn' => '9788426416202',
                'genero_id' => '1',
                
            ],
            [
                'isbn' => '9788491819873',
                'genero_id' => '2',
               
            ],
            [
                'isbn' => '9788426415202',
                'genero_id' => '1',
                
            ],
            // ...
        ];

        foreach ($libros as $libro) {
            $genero = Genero::find($libro['genero_id']);
            if ($genero) {
                $libro = Libro::where('isbn', $libro['isbn'])->first();
                if ($libro) {
                    $libro->generos()->attach($genero);
                }
            }
        }

        
    }
}
