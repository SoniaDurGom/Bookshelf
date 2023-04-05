<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Genero extends Model
{
    use HasFactory;


    //Un genero puede pertenecer a varios libros
    public function libros()
    {
        return $this->belongsToMany(Libro::class, 'generos_libro');
    }

    //Los lectores pueden tener varios generos favoritos
    public function lectores()
    {
        return $this->belongsToMany(Lector::class, 'lectores_generos'); //lectores_generos es la tabla intermedia de la n-n
    }








}
