<?php

namespace App\Http\Controllers;

use App\Models\Libro;
use App\Models\Reto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $lector = Auth::guard('lector')->user();
        // $librerias-> sacar 3
        $leyendo = $lector->librerias->where('nombre', 'Leyendo')->first();
        $lecturasLeyendo = $leyendo->lecturas()->inRandomOrder()->take(1)->get();
        

        $pendientes = $lector->librerias->where('nombre', 'Pendientes')->first();
        $lecturasPendientes = $pendientes->lecturas()->inRandomOrder()->take(1)->get();

        // $lecturasPendientes = $lecturasPendientes->chunk(1);

        // Obtener el lector logueado
        $perfil = Auth::guard('lector')->user();
        // Obtener los géneros favoritos del lector
        $generos = $perfil->generosFavoritos->pluck('genero_id');
        // Obtener los libros con puntuación de 4 estrellas o más
        $libros = Libro::with(['generos', 'valoraciones'])
            ->whereHas('valoraciones', function($query) {
                $query->where('puntuacion', '>=', 4);
            })->inRandomOrder()->take(1)->get();
        // Filtrar los libros por géneros favoritos del lector
        $recomendacion = $libros->filter(function($libro) use ($generos) {
            return $libro->generos->whereIn('id', $generos)->count() > 0;
        });


        // $reto
        $reto= Reto::all();

         return view('home', compact('recomendacion', 'lecturasLeyendo', 'lecturasPendientes','reto','perfil'));
    }

   
}
