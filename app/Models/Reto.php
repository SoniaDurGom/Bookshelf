<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reto extends Model
{
    use HasFactory;

    protected $fillable = [
        'anio',
        'libros_objetivo',
        'libros_leidos',
        'completado',
        'lector_id',
    ];

    public function lector()
    {
        return $this->belongsTo(Lector::class);
    }

    public function lecturas()
    {
        return $this->hasManyThrough(Lectura::class, Lector::class);
    }
    

}
