<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Autor_Sin_Cuenta;

class AutorSinCuentaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Autor_Sin_Cuenta::create([
            'nombre' => 'Gabriel',
            'apellidos' => 'García Márquez',
        ]);

        Autor_Sin_Cuenta::create([
            'nombre' => 'Jorge Luis',
            'apellidos' => 'Borges',
        ]);

        Autor_Sin_Cuenta::create([
            'nombre' => 'Isabel',
            'apellidos' => 'Allende',
        ]);
    }
}
