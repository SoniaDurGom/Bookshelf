<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;

class Lector extends Authenticatable
{
    use HasFactory;

    protected $table = 'lectores';

    protected $fillable = [
        'perfil_id',
    ];


    //Un lector tiene un perfil
    public function perfil()
    {
        return $this->belongsTo(Perfil::class, 'perfil_id');
    }

    //Un lector puede tener varios retos. Pero es uno al año.
    public function reto()
    {
        return $this->hasOne(Reto::class);
    }

    //Un Lector tiene varias lecturas
    public function lecturas()
    {
        return $this->hasMany(Lectura::class);
    }

    //Un lector escribe varias valoraciones, una por libro
    public function valoraciones()
    {
        return $this->hasMany(Valoracion::class);
    }

    //Un Lector tiene varias librerias
    public function librerias()
    {
        return $this->hasMany(Libreria::class);
    }

    //Un lector tiene generos favoritos, hasta 3.
    public function generosFavoritos()
    {
        return $this->belongsToMany(Genero::class, 'lectores_generos')->withTimestamps();
    }


  
   



}


