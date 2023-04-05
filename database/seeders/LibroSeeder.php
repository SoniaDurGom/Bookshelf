<?php

namespace Database\Seeders;

use App\Models\Libro;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LibroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        echo "Ejecutando seeder de Libros...\n";
        $libros = [
            [
                'isbn' => '9788491819773',
                'portada' => 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1401798323i/19480636.jpg',
                'titulo' => 'Irene',
                'notamedia' => 4.5,
                'numero_valoraciones' => 10,
                'fecha_publicacion' => '1967-06-30',
                'numero_paginas' => 448,
                'editorial_id' => 1,
            ],
            [
                'isbn' => '9788426416202',
                'portada' => 'https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1540374201i/42428455.jpg',
                'titulo' => 'El Psicoanalista',
                'notamedia' => 4.0,
                'numero_valoraciones' => 5,
                'fecha_publicacion' => '1605-01-16',
                'numero_paginas' => 992,
                'editorial_id' => 1,
            ],
            // ...
        ];

        foreach ($libros as $libro) {
            Libro::create($libro);
        }
    }
}
