<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Libro extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'isbn',
        'portada',
        'titulo',
        'fecha_publicacion',
        'num_paginas',
    ];


    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    //1 libro pertenece a una editorial
    public function editorial()
    {
        return $this->belongsTo(Editorial::class);
    }

    //Un libro tiene varios autores
    public function autorSinCuenta()
    {
        return $this->belongsToMany(Autor_Sin_Cuenta::class, 'autores_libros', 'libro_id', 'autor_id');
    }

    //1 libro tiene varias fichas de lectura, pertenecientes a los usuarios
    public function lectura()
    {
        return $this->hasMany(Lectura::class);
    }

    //Un libro puede tener muchos generos
    public function generos()
    {
        return $this->belongsToMany(Genero::class, 'generos_libro');
    }

    //Un libro tiene valoraciones de los Lectores. (Los lectores escriben sus valoreciones desde las fichas de lectura...)
    public function valoraciones()
    {
        return $this->hasMany(Valoracion::class);
    }

   


}