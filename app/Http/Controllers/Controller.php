<?php

namespace App\Http\Controllers;

use App\Models\Libro;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;


class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;


    public function conocenos()
    {
        $perfil=null;
        if(Auth::guard('lector')->user()){
            $perfil = Auth::guard('lector')->user();
        }
        if(Auth::guard('autores')->user()){
            $perfil = Auth::guard('autores')->user();
        }

        return view('etc.conocenos', compact('perfil'));
    }


    public function terminosYcondiciones()
    {
        $perfil=null;
        if(Auth::guard('lector')->user()){
            $perfil = Auth::guard('lector')->user();
        }
        if(Auth::guard('autores')->user()){
            $perfil = Auth::guard('autores')->user();
        }
        return view('etc.terminos-y-condiciones', compact('perfil'));
    }


    public function buscar(Request $request)
    {
        
        $perfil = Auth::guard('lector')->user();
        $query = $request->input('query');
        $librosBusqueda = Libro::where('titulo', 'LIKE', '%'.$query.'%')->get();
        return view('libros.busqueda', compact('librosBusqueda', 'perfil'));
    }

}
