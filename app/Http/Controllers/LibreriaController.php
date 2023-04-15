<?php

namespace App\Http\Controllers;

use App\Models\Lectura;
use App\Models\Libreria;
use App\Models\Libro;
use App\Services\LecturaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LibreriaController extends Controller
{

    private $lecturaService;

    public function __construct(LecturaService $lecturaService)
    {
        $this->lecturaService = $lecturaService;
    }

    public function agregarLibroALibreria(Request $req)
    {
        $libroId = $req->libro_id;
        $libreriaId = $req->libreria_id;

        if ($this->lecturaService->crearLectura($libroId, $libreriaId)) {
            return redirect()->back()->with('success', 'Libro agregado a la librería con éxito.');
        } else {
            return redirect()->back()->with('error', 'Ya existe una lectura para este libro y usuario.');
        }
    }
    
    public function mostrarLibrerias()
    {
       
        $perfil = Auth::guard('lector')->user();
        $lector_id = auth()->guard('lector')->id();
        $librerias = Libreria::where('lector_id', $lector_id)->get();
        
        
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

    


    // public function agregarLibroALibreria(Request $req)
    // {
    //     $lectorId = Auth::guard('lector')->user()->id;
    //     $libroId=$req->libro_id;
    //     $libreriaId=$req->libreria_id;
        
    //     //Si ya hay una ficha de lectura para un usuario de un libro, no se crea otra-
    //     $existeLectura = Lectura::where('libro_id', $libroId)
    //                                 ->where('lector_id', $lectorId)
    //                                 ->exists();
    //     if ($existeLectura) {
    //         return redirect()->back()->with('error', 'Ya existe una librería con este nombre');
    //     }

    //     $lectura = new Lectura;
    //     $lectura->libro_id = $lectorId;
    //     $lectura->lector_id = $lectorId;
    //     $lectura->libreria_id = $libreriaId;
    //     $lectura->save();
        
    //     // Agregar el libro a la librería y guardar los cambios en la base de datos.
    //     // $libreria->lecturas->attach($libro);
        
    //     return redirect()->back()->with('success', 'Libro agregado a la librería con éxito.');
    // }
    

    public function eliminarLibroDeLibreria($libreriaId, $libroId)
    {
        $libreria = Libreria::find($libreriaId);
        $libreria->libros()->detach($libroId);
    }


    // Saca los libros de una libreria del lector logueado
    public function abrirLibreria($nombre)
    {
        $perfil = Auth::guard('lector')->user();
        // Obtener la librería y sus libros relacionados
        if ($nombre == 'todos') {
            $libreriaSeleccionada  = $perfil->librerias; //Array con todas las librerias
        } else {
            $libreriaSeleccionada = $perfil->librerias->where('nombre', $nombre)->firstOrFail(); //La libreria que coincide con el nombre
          
        }
        $allLibrerias = $perfil->librerias; //Todas las librerias del autor

        $numero_de_lecturas_por_libreria = [];
        foreach ($allLibrerias as $libreria) {
            $numero_de_lecturas = Lectura::where('libreria_id', $libreria->id)->count();
            $numero_de_lecturas_por_libreria[$libreria->id] = $numero_de_lecturas;
        }
        $num_total_lecturas = $perfil->lecturas->count();
        // dd($num_total_lecturas);

       

        // Retornar la vista con la librería y sus libros
        return view('librerias.libreriasLector', compact('libreriaSeleccionada', 'perfil', 'allLibrerias', 'num_total_lecturas', 'numero_de_lecturas_por_libreria'));
    }





}
