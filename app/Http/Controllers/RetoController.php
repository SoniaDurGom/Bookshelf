<?php

namespace App\Http\Controllers;

use App\Models\Lector;
use App\Models\Reto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class RetoController extends Controller
{
    public function mostrar()
    {
        $perfil = Auth::guard('lector')->user();
        
        $reto= $this->retoActual($perfil); //Saca el reto del lector
        $totalPaginasLeidas= $this->actualizarProgresoReto();
        if ($reto->completado) {
            Session::flash('success', '¡Felicitaciones! Has completado el reto de lectura.');
        }

        return view('reto.index', compact('perfil', 'reto','totalPaginasLeidas'));
        
    
    }


    public function retoActual($lector)
    {
        $anio = date('Y');
        $reto = $lector->reto->where('anio', $anio)->first();
    
        if (!$reto) { //Si no existe un reto para el año actual se crea uno con el año actual
            $reto = $lector->reto->create([
                'anio' => date('Y'),
                'libros_objetivo' => 0,
                'libros_leidos' => 0,
                'lecturas_leidas' => 0,
                'completado' => false,
            ]);
        }
    
        return $reto;
    }
    

    // public function actualizarReto(Request $request)
    // {
    //     $perfil = Auth::guard('lector')->user();
    //     $reto = $perfil->retoActual();

    //     $reto->update([
    //         'libros_objetivo' => $request->libros_objetivo,
    //     ]);

    //     return view('reto.index', compact('libros', 'perfil'));
        
    
    // }

//     public function update(Request $request, Reto $reto)
// {
//     $request->validate([
//         'libros_objetivo' => 'required|string|max:255',
//         'completado' => 'required|boolean',
//     ]);

//     $reto->libros_objetivo = $request->libros_objetivo;
//     $reto->completado = $request->completado;
//     $reto->save();

//     return redirect()->route('reto.mostrar', $reto->id);
// }



    public function marcarObjetivo(Request $request)
    {
        $perfil = Auth::guard('lector')->user();
        $validatedData = $request->validate([
            'libros_objetivo' => 'required|integer|min:1',
        ]);

        $reto= $this->retoActual($perfil); //Saca el reto del lector
    
        $reto->libros_objetivo = $validatedData['libros_objetivo'];
   
        $reto->save();

        return redirect()->route('reto.mostrar')->with('success', 'Reto actualizado'); //!Mostrar mensaje al uasuario
    }


    // Se llamará a este metodo cada vez que el usuario marque todas las páginas del libro como leidas o actualice la ficha de lectura al estado=Terminado
    //Si un lectura dentro de los retos (lector->lectura) que fue marcada como terminada, se cambia de estado, se borra del progreso
    public function actualizarProgresoReto()
    { 
        $perfil = Auth::guard('lector')->user();
        // $lector = Auth::guard('lector')->user();
        $reto= $this->retoActual($perfil); //Saca el reto del lector
        $totalPaginasLeidas=0;
        $reto->libros_leidos=0;
        
        foreach ($perfil->lecturas as $lectura) {
            if($lectura->paginas_leidas == $lectura->libro->numero_paginas){
                $lectura->update(['estado' => 'Leido']);
                $reto->libros_leidos++;
                $totalPaginasLeidas += $lectura->paginas_leidas;
            }
        }

        if ($reto->libros_leidos >= $reto->libros_objetivo) {
            $reto->completado = 1;
        } else {
            $reto->completado = 0;
        }


        $reto->save();

        // Se redirige a la pagina anterior
        return $totalPaginasLeidas;
    }



}
