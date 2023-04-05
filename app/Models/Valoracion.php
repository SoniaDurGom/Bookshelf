<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Valoracion extends Model
{
    use HasFactory;

    //Una valoracion pertenece a un libro
    public function libro()
    {
        return $this->belongsTo(Libro::class);
    }

    //Una valoracion pertenece a un lector
    public function lector()
    {
        return $this->belongsTo(Lector::class);
    }






}
