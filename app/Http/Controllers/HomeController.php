<?php

namespace App\Http\Controllers;

use App\Models\Autor;
use App\Models\Lector;
use App\Models\Lectura;
use App\Models\Libreria;
use App\Models\Libro;
use App\Models\Perfil;
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
        $perfil = Auth::guard('lector')->user();
        // $librerias-> sacar 3
        $leyendo = Libreria::where('nombre', 'Leyendo')->first();
        $lecturasLeyendo = $leyendo->lecturas()->inRandomOrder()->take(3)->get();
       
        $pendientes = Libreria::where('nombre', 'Quiero Leer')->first();
        $lecturasPendientes = $pendientes->lecturas()->inRandomOrder()->take(6)->get();
        
       
        // Obtener el lector logueado
        $lector = Auth::guard('lector')->user();
        $lector_id=$lector->id;
        // Obtener los géneros favoritos del lector
        $generos = $lector->generosFavoritos()->pluck('generos.id');
        
        //Libros con los generos favoritos de lector y con valoraciones superiores a 4
        $libros_recomendados = Libro::with(['generos', 'valoraciones'])
            ->whereHas('valoraciones', function($query) {
                $query->where('puntuacion', '>=', 4);
            })->get();

        //Libros en todas las librerias del lector actual
        $libros_en_librerias = Libro::whereIn('id', function ($query) use ($lector_id) {
            $query->select('libro_id')->from('lecturas')->whereIn('libreria_id', function ($query) use ($lector_id) {
                $query->select('id')->from('librerias')->where('lector_id', $lector_id);
            });
        })->get();

        //Se obtiene la diferencia entre los libros recomendados y los libros que ya estan en alguna libreria.
        $libros_recomendados = $libros_recomendados->diff($libros_en_librerias);
        // $libro_aleatorio = $libros_recomendados->random();
        $autores_con_perfil = Autor::has('perfil')->get();

        // dd($libro_aleatorio);




        
       //librerias
       $librerias= Libreria::all();
       $numero_de_lecturas_por_libreria = [];
        foreach ($librerias as $libreria) {
            $numero_de_lecturas = Lectura::where('libreria_id', $libreria->id)->count();
            $numero_de_lecturas_por_libreria[$libreria->id] = $numero_de_lecturas;
        }
        $num_total_lecturas = $perfil->lecturas->count();

        
     

        // $reto
        $reto= Reto::all();

         return view('home', compact('autores_con_perfil','libros_recomendados', 'lecturasLeyendo', 'lecturasPendientes','librerias','numero_de_lecturas_por_libreria','num_total_lecturas','reto','perfil'));
    }

   
}
