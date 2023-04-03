<?php

namespace App\Http\Controllers;

use App\Models\Lector;
use App\Models\Perfil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LectorController extends Controller
{
    /**
     * Muestra el formulario de registro de lectores.
     *
     * @return \Illuminate\Http\Response
     */
    public function formulario()
    {
        return view('auth.login');
    }


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

        $lector = $perfil->lector;

        if (! $lector) {
            return back()->withErrors([
                'email' => 'El perfil no está asociado con ninguna cuenta.',
            ]);
        }

        Auth::guard('lector')->login($lector);

        return redirect()->route('lectores.panelControl');
    }
        







    /**
     * Almacena un nuevo lector en la base de datos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function guardar(Request $request)
    {
        
        // Validar los datos del formulario.
        $datosValidados = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:perfiles|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Crear el perfil del lector.
        $perfil = new Perfil;
        $perfil->name = $datosValidados['name'];
        $perfil->email = $datosValidados['email'];
        $perfil->password = bcrypt($datosValidados['password']);
        $perfil->save();

        //CrearLector
        $lector = new Lector();
        $lector->perfil_id = $perfil->id;
        // dd($lector);
        $lector->save();

        // Autenticar al lector y redireccionarlo a su panel de control.
        // $lecor = Auth::guard('lector')->user();
        // $nombre = $administrador->perfil->name;

        Auth::login($lector);
        return redirect('/lectores/panel-control');
    }

    /**
     * Muestra el panel de control del lector.
     *
     * @return \Illuminate\Http\Response
     */
    public function panelControl()
    {
        // Obtener el perfil del lector.
        $lector = Auth::guard('lector')->user();
        $nombre = $lector->perfil->name;
        // dd($nombre);
        return view('lectores_panelControl', compact('nombre'));

        // return view('lectores.panelControl', [
        //     'perfil' => $lector,
        // ]);
    }
}
