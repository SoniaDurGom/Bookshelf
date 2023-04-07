<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Perfil;
use Illuminate\Support\Facades\Hash;

class AdministradorController extends Controller
{
    /**
     * Muestra el formulario de inicio de sesión de administradores.
     *
     * @return \Illuminate\Http\Response
     */
    public function mostrarFormularioLogin()
    {
        return view('auth.admin-login');
    }

    /**
     * Inicia sesión al administrador.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    $perfil = Perfil::where('email', $credentials['email'])->first();

    if (! $perfil || ! Hash::check($credentials['password'], $perfil->password)) {
        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no son válidas.',
        ]);
    }

    $admin = $perfil->administrador;

    if (! $admin) {
        return back()->withErrors([
            'email' => 'El perfil no está asociado con ningún administrador.',
        ]);
    }

    Auth::guard('administradores')->login($admin);

    return redirect()->route('administradores.panelControl');
}
    

    /**
     * Cierra sesión del administrador.
     *
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        Auth::guard('administradores')->logout();
        return redirect('/');
    }

    /**
     * Muestra el panel de control del administrador.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function panelControl()
    {
        $administrador = Auth::guard('administradores')->user();
        // $nombre = $administrador->perfil->name;
        // dd($nombre);
        // return view('auth.administradores_panelControl', compact('nombre'));

        return view('auth.administradores_panelControl', [
            'perfil' => $administrador,
        ]);

        
    }




    
}
