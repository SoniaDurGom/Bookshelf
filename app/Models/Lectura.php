<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lectura extends Model
{

    use HasFactory;

    protected $fillable = [
        'fecha_inicio',
        'fecha_fin',
        'paginas_leidas',
        'estado',
        'libro_id',
        'lector_id'
    ];

    //Una lectura pertenece a un libro
    public function libro()
    {
        return $this->belongsTo(Libro::class);
    }

    //Una lectura pertenece a un lector, cada uno tiene su ficha de lectura
    public function lector()
    {
        return $this->belongsTo(Lector::class);
    }

    //Una lectura está en una libreria
    public function libreria()
    {
        return $this->belongsTo(Libreria::class);
    }






}
