<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Foundation\Auth\User as Authenticatable;
class Administrador extends Authenticatable
{
    use HasFactory;

    protected $table = 'administradores';

    protected $fillable = [
        'perfil_id',
    ];

    public function perfil()
    {
        return $this->belongsTo(Perfil::class, 'perfil_id');
    }
}
