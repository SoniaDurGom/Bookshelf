<?php

namespace App\Http\Controllers;

use App\Models\Lectura;
use App\Models\Libreria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LibreriaController extends Controller
{
    
    public function mostrarLibrerias()
    {
        $librerias = Libreria::all();
        $perfil = Auth::guard('lector')->user();
        $lector_id = auth()->guard('lector')->id();
        
        $lecturas = Lectura::where('lector_id', $lector_id)->get();
        return view('librerias.libreriasLector', compact('librerias', 'perfil', 'lecturas'));
    }
    
    
    // Muestra las lecturas guardadas en la libreria seleccionada por nombre
    public function mostrarLibros($nombreLibreria)
    {
        $perfil = Auth::guard('lector')->user();
        $libreria = Libreria::where('nombre', $nombreLibreria)->firstOrFail();
        $lecturas = $libreria->lecturas()->with('libro')->get();
        $libros = $libreria->libros;

        return view('librerias.libros', compact('libros', 'libreria', 'lecturas', 'perfil'));
    }




    public function crearLibreria(Request $request)
    {
        $nombre = $request->input('nuevaLibreria');
        $lector_id = Auth::guard('lector')->user()->id;

        // comprobar si ya existe una librería con ese nombre para el mismo lector
        $existeLibreria = Libreria::where('nombre', $nombre)
                                    ->where('lector_id', $lector_id)
                                    ->exists();
        if ($existeLibreria) {
            return redirect()->back()->with('error', 'Ya existe una librería con este nombre');
        }

        $libreria = new Libreria;
        $libreria->nombre = $nombre;
        $libreria->lector_id = $lector_id;
        $libreria->save();

        return redirect()->back()->with('message', 'Librería creada correctamente');
    }



    // Borra librerias del Lector activo, Este no podrá borrar las 3 librerias por defecto
    //Leyendo, Leido y Quiero leer
    public function borrarLibreria($id)
    {
        
        $libreria = Libreria::find($id);

        //Aqui no deberia poder llegarse, ya que el boton borrar solo se vera en las librerias que no tengan estos nombres
        if ($libreria) {
            if ($libreria->nombre === 'Leyendo' || $libreria->nombre === 'Leído' || $libreria->nombre === 'Quiero leer') {
                return redirect()->back()->with('message', 'Esta libreria no se puede eliminar');
            }else{
                $libreria->delete();
                return redirect()->back()->with('message', 'Librería eliminada correctamente');
            }
        } else {
            return redirect()->back()->with('message', 'Librería no encontrada');
        }
    }


    public function agregarLibroALibreria($libreriaId, $libroId)
    {
        $libreria = Libreria::find($libreriaId);
        $libreria->libros()->attach($libroId);
    }

    public function eliminarLibroDeLibreria($libreriaId, $libroId)
    {
        $libreria = Libreria::find($libreriaId);
        $libreria->libros()->detach($libroId);
    }


    // Saca los libros de una libreria del lector logueado
    public function abrirLibreria($nombre)
    {
        // Obtener la librería y sus libros relacionados
        if ($nombre == 'todos') {
            $librerias  = Libreria::with('lecturas')->get();
        } else {
            $librerias = Libreria::with('lecturas')->where('nombre', $nombre)->firstOrFail();
        }
        $perfil = Auth::guard('lector')->user();

        // Retornar la vista con la librería y sus libros
        return view('librerias.lecturas', compact('librerias', 'perfil'));
    }





}
