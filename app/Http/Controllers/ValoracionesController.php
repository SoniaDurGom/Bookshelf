<?php

namespace App\Http\Controllers;

use App\Models\Valoracion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ValoracionesController extends Controller
{

    public function guardarValoracion(Request $request)
    {
        // dd( $request);
        // Obtener la valoración y el comentario del usuario desde el formulario
        $comentario = $request->input('comentario');
        $puntuacion = $request->input('puntuacion');
        $libro_id = $request->input('libro_id');
        $lector_id = Auth::guard('lector')->user()->id;
       
        // Validar que se haya seleccionado al menos una estrella
        if ($puntuacion < 1) {
            // Si no se ha seleccionado ninguna estrella, redirigir al usuario con un mensaje de error
            return redirect()->back()->with('error', 'Debe seleccionar al menos una estrella.');
        }

        $validator = Validator::make($request->all(), [
            'comentario' => 'required|max:500',
            'puntuacion' => 'required|integer|between:1,5',
        ]);
        
        // Validar que no exista ya una valoración para el mismo libro y lector
        $validator->after(function ($validator) use ($request) {
            $libro_id = $request->input('libro_id');
            $lector_id = Auth::guard('lector')->user()->id;
            
            if (Valoracion::where('libro_id', $libro_id)->where('lector_id', $lector_id)->exists()) {
                $validator->errors()->add('unique', 'Ya has valorado este libro anteriormente.');
            }
        });
        
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        
        
        // Crear un nuevo objeto de Valoracion y guardar los datos en la base de datos
        $valoracion = new Valoracion();
        $valoracion->comentario = $comentario;
        $valoracion->puntuacion = $puntuacion;
        $valoracion->libro_id = $libro_id;
        $valoracion->lector_id = $lector_id;

        $valoracion->save();
        
        // Redirigir al usuario con un mensaje de éxito
        return redirect()->back()->with('success', 'Valoración guardada con éxito.');
    }


}
