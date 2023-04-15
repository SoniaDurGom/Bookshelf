<?php

namespace App\Http\Controllers;

use App\Models\Lectura;
use App\Models\Libro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LecturaController extends Controller
{
    public function actualizar(Request $request, $id)
    {
       
        $lectura = Lectura::findOrFail($id);
        $libro = Libro::find($lectura->libro_id);

        $rules = [
            'fecha_inicio' => 'nullable|date|before_or_equal:today',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio|before_or_equal:today',
            'paginas_leidas' => 'nullable|integer|min:1|max:'. $libro->numero_paginas,
        ];
    
        $messages = [
            'fecha_inicio.date' => 'La fecha de inicio no tiene un formato válido.',
            'fecha_inicio.before_or_equal' => 'La fecha de inicio no puede ser superiro a la fecha actual.',
            'fecha_fin.date' => 'La fecha de finalización no tiene un formato válido.',
            'fecha_fin.after_or_equal' => 'La fecha de finalización no puede ser anterior a la fecha de inicio.',
            'fecha_fin.before_or_equal' => 'La fecha de finalización no puede ser superior a la fecha actual.',
            'paginas_leidas.integer' => 'Las páginas leídas deben ser un número entero.',
            'paginas_leidas.min' => 'Las páginas leídas no pueden ser menores que 1.',
            'paginas_leidas.max' => 'Las páginas leídas no pueden ser más que las páginas del libro.',
        ];
    
        $validator = Validator::make($request->all(), $rules, $messages);
    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }


        // if($request->estado="Leido")

        if ($request->paginas_leidas == $libro->numero_paginas) {
            $lectura->fill([
                'fecha_inicio' => $request->fecha_inicio,
                'fecha_fin' => $request->fecha_fin,
                'paginas_leidas' => $request->paginas_leidas,
                'estado' => 'Leido'
            ]);
        } else {
            $lectura->fill([
                'fecha_inicio' => $request->fecha_inicio,
                'fecha_fin' => $request->fecha_fin,
                'paginas_leidas' => $request->paginas_leidas,
                'estado' => $request->estado
            ]);
            
            if ($request->estado == 'Leido') {
                $lectura->paginas_leidas = $libro->numero_paginas;
            }
        }
        
        $lectura->save();
        
        
        
        
        
        return redirect()->back()->with('success', 'La ficha de lectura ha sido actualizada exitosamente.');
    }
    

    
    
}
