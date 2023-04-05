<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Libreria extends Model
{
    use HasFactory;


    //Una libreria pertenece a un lector
    public function lector()
    {
        return $this->belongsTo(Lector::class);
    }

    //Una libreria tiene varios libros
    public function libros()
    {
        return $this->hasMany(Libro::class);
    }




}
