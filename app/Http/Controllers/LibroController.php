<?php

namespace App\Http\Controllers;

use App\Models\Autor;
use App\Models\Genero;
use App\Models\Libro;
use App\Models\Perfil;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LibroController extends Controller
{
    public function index()
    {
        $libros = Libro::all();
        $perfil = Auth::guard('lector')->user();

        return view('libros.index', compact('libros', 'perfil'));
    }


    public function librosPorGenero($genero_nombre)
    {
        $perfil = Auth::guard('lector')->user();
        $genero = Genero::where('nombre', $genero_nombre)->firstOrFail(); 
        $libros = $genero->libros;
        
        return view('libros.por_genero', compact('libros', 'genero', 'perfil'));
    }


    public function todosLosGeneros()
    {
      
        $perfil = Auth::guard('lector')->user();
        $generos = Genero::with('libros')->get();
        // dd("generos");
        return view('libros.index', compact('generos', 'perfil'));
        
    
    }

    public function fichaLibro($id)
    {
        $perfil = Auth::guard('lector')->user();
        $libro = Libro::find($id);
        // $autorRegistrado=null;

        $valoraciones = $libro->valoraciones;

        $suma_puntuaciones = 0;
        $num_valoraciones = count($valoraciones);
    
        foreach ($valoraciones as $valoracion) {
            $suma_puntuaciones += $valoracion->puntuacion;
        }
    
        $media = $num_valoraciones > 0 ? round($suma_puntuaciones / $num_valoraciones, 1) : null;


        // Busca autor registrado
        foreach ($libro->autorSinCuenta as $autorLibro) {
            $autorLibroNombre= $autorLibro->nombre . " " . $autorLibro->apellidos;
            $autorRegistrado = null;
            $perfilRegistrado= Perfil::where('name', $autorLibroNombre)->first();
            if($perfilRegistrado!=null){
                $autorRegistrado = $perfilRegistrado->autor;
            }
           

            break;
        }
        // dd($autorRegistrado);   

        //Sacar las valoraciones del libro

        return view('libros.fichaLibro', compact('libro', 'perfil', 'media','autorRegistrado'));
    }


    public function novedades()
    {
      
        $perfil = Auth::guard('lector')->user();
    
        // Obtener la fecha de hace 6 meses a partir de la fecha actual
        $fechaLimite = Carbon::now()->subMonths(6);

        // Obtener todos los libros que hayan sido publicados en los últimos 6 meses
        $libros = Libro::whereDate('fecha_publicacion', '>=', $fechaLimite)
                    ->get();

        return view('libros.novedades', compact('libros', 'perfil'));
        
    
    }




    public function recomendaciones()
    {
        // Obtener el lector logueado
        $perfil = Auth::guard('lector')->user();
        $lector = $perfil;
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

        // Retornar la vista con las recomendaciones
        return view('libros.recomendaciones', compact('libros_recomendados', 'perfil'));
    }
    
    
   


   


}
