<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class AdminLoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/admin/dashboard';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.admin-login');
    }

    public function login(Request $request)
    {
        // Validar las credenciales del usuario
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:6',
            
        ]);

        // Intentar iniciar sesión del administrador
        if (auth()->guard('admin')->attempt($request->only('email', 'password'))) {
            // Si las credenciales son válidas, iniciar sesión y redirigir al dashboard de administrador
            return redirect()->intended('/admin/dashboard');
        }

        // Si las credenciales son inválidas, volver al login con un mensaje de error
        return back()->withInput($request->only('email'))->withErrors([
            'email' => 'Estas credenciales no coinciden con nuestros registros.',
        ]);
    }
}
