<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;


    public function conocenos()
    {
        $perfil=null;
        if(Auth::guard('lector')->user()){
            $perfil = Auth::guard('lector')->user();
        }
        if(Auth::guard('autores')->user()){
            $perfil = Auth::guard('autores')->user();
        }

        return view('etc.conocenos', compact('perfil'));
    }


    public function terminosYcondiciones()
    {
        $perfil=null;
        if(Auth::guard('lector')->user()){
            $perfil = Auth::guard('lector')->user();
        }
        if(Auth::guard('autores')->user()){
            $perfil = Auth::guard('autores')->user();
        }
        return view('etc.terminos-y-condiciones', compact('perfil'));
    }
}
