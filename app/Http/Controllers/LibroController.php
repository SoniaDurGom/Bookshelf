<?php

namespace App\Http\Controllers;

use App\Models\Genero;
use App\Models\Libro;
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



   


}
