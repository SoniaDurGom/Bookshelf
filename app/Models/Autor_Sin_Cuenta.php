<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Autor_Sin_Cuenta extends Model
{
    use HasFactory;
    protected $table = 'autores_sin_cuenta';

    protected $fillable = [
        'nombre',
        'apellidos',
    ];

    public function libros()
    {
        return $this->belongsToMany(Libro::class, 'autores_libros', 'autor_id', 'libro_id')->withTimestamps();
    }

    public function getNombreCompletoAttribute()
    {
        return $this->nombre . ' ' . $this->apellidos;
    }
}
