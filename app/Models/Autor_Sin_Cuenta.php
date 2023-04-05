<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Autor_Sin_Cuenta extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'apellidos',
    ];

    //Un autor tiene varios libros
    public function libros()
    {
        return $this->belongsToMany(Libro::class);
    }
}
