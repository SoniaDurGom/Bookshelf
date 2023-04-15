<?php

namespace App\Services;

use App\Models\Lectura;
use Illuminate\Support\Facades\Auth;

class LecturaService
{
    public function crearLectura($libroId, $libreriaId)
{
    $lectorId = Auth::guard('lector')->user()->id;

    // Buscar una lectura existente para este libro y usuario.
    $lectura = Lectura::where('libro_id', $libroId)
                      ->where('lector_id', $lectorId)
                      ->first();

    if ($lectura) {
        // Si ya existe una lectura para este libro y usuario, actualizar la librería si es diferente.
        if ($lectura->libreria_id != $libreriaId) {
            $lectura->libreria_id = $libreriaId;
            $lectura->save();
        }
    } else {
        // Si no existe una lectura para este libro y usuario, crear una nueva lectura.
        $lectura = new Lectura;
        $lectura->libro_id = $libroId;
        $lectura->lector_id = $lectorId;
        $lectura->libreria_id = $libreriaId;
        $lectura->save();
    }

    return $lectura;
}

}
