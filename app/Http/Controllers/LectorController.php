<?php

namespace App\Http\Controllers;

use App\Models\Lector;
use App\Models\Perfil;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;//Para subir fotos


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

        Auth::guard('lector')->login($lector);

        return redirect()->route('lectores.panelControl');
    }

    /**
     * Muestra el panel de control del lector.
     *
     * @return \Illuminate\Http\Response
     */
    public function panelControl()
    {
        
        $lector = Auth::guard('lector')->user();
        return view('lectores_panelControl', [
            'perfil' => $lector,
        ]);
    }





    public function subirFoto(Request $request)
    {
        // Validar que se ha enviado una foto
        $request->validate([
            'foto' => 'required|image|mimes:jpeg,png,jpg,svg|max:2048', // 2MB como máximo
        ]);

        // Obtener el archivo de la solicitud
        $foto = $request->file('foto');

        // Generar un nombre único para la foto
        $nombreFoto = uniqid() . '.' . $foto->getClientOriginalExtension();

        // Almacenar la foto en el sistema de archivos
        Storage::disk('public')->putFileAs(
            'fotos-perfil',
            $foto,
            $nombreFoto
        );
        


        // Actualizar la columna "foto" en la tabla "lectores" con la ruta a la foto
        $lector = Lector::find(auth()->id());
        $lector->foto = '/storage/fotos-perfil/' . $nombreFoto;
        $lector->save();

        // Redirigir a la página de perfil
        return redirect()->route('lectores.panelControl')->with('success', 'La foto de perfil se ha subido correctamente.');
    }


    public function borrarCuenta()
    {
        $lector = Auth::guard('lector')->user();
        $perfil = $lector->perfil;

        // Eliminar la foto del perfil si existe
        if ($perfil->foto) {
            Storage::disk('public')->delete($perfil->foto);
        }

        // Eliminar el perfil del lector y todas las relaciones
        $perfil->delete();

        // Desautenticar al lector y redireccionarlo al formulario de inicio de sesión
        Auth::guard('lector')->logout();

        return redirect()->route('login')->with('success', 'La cuenta se ha eliminado correctamente.');
    }


    public function formularioAjustes()
    {

        $lector = Auth::guard('lector')->user();
        return view('lector-formulario-ajustes', [
            'perfil' => $lector,
        ]);
    }

    public function cambiarAjustes(Request $request)
    {
        $lector = Auth::guard('lector')->user();
        $perfil = $lector->perfil;

        
       
        $datosValidados = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:perfiles|max:255',
            'password' => 'nullable|string|min:8|confirmed',
        ]);
        // dd($request);
        
        $perfil->name = $datosValidados['name'];
        $perfil->email = $datosValidados['email'];
        if (isset($datosValidados['password']) && $perfil->password != $datosValidados['password']) {
            $perfil->password = bcrypt($datosValidados['password']);
        }

      

        $perfil->save();

        Auth::guard('lector')->login($lector);
        // Redirigir a la página de perfil
        return redirect()->route('lectores.panelControl')->with('success', 'La foto de perfil se ha subido correctamente.');
    }


    //TODO: 
    public function guardarGenerosFavoritos(Request $request)
    {
        $lector = Auth::guard('lector')->user();
        $perfil = $lector->perfil;

        $request->validate([
            'generos' => 'array|max:3',
            'generos.*' => 'exists:generos,id',
        ]);

        $perfil->generosFavoritos()->sync($request->generos);

        return redirect()->route('perfiles.show', $perfil);
    }




}
