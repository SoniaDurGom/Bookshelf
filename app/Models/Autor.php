<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Autor extends Authenticatable
{
    use HasFactory;

    protected $table = 'autores';

    protected $fillable = [
        'perfil_id',
        'biografia',
        'aprobado',
    ];

    public function perfil()
    {
        return $this->belongsTo(Perfil::class, 'perfil_id');
    }
}
