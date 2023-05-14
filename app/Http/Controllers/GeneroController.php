<?php

namespace App\Http\Controllers;

use App\Models\Lector;
use App\Models\Genero;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GeneroController extends Controller
{
    public function guardarFavoritos(Request $request)
    {
        $lector = Auth::guard('lector')->user();
        $request->validate([
            'favoritos' => 'required|max:3',
        
        ], [
            'favoritos.required' => 'Debes seleccionar al menos un genero favorito',
            'favoritos.max' => 'Máximo actual de generos permitidos: 3'
        ]);
       
        // actualizar los géneros favoritos del lector en la base de datos
        
        $lector->generosFavoritos()->sync($request->input('favoritos'));

        return redirect()->back()->with('success', 'Se han guardado los géneros favoritos correctamente.');
        }
    }

    
