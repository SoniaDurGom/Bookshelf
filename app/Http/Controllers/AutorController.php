<?php


namespace App\Http\Controllers;

use App\Models\Autor;
use App\Models\Perfil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AutorController extends Controller
{
    /**
     * Muestra el formulario de registro de autores.
     *
     * @return \Illuminate\Http\Response
     */
    public function formulario()
    {
        return view('auth.autor-login');
    }

    /**
     * Almacena un nuevo autor en la base de datos y lo envía a revisión.
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
            'biografia' => 'nullable|string|max:500',
        ]);
        // dd( $datosValidados);

       

        // Crear el perfil del autor.
        $perfil = new Perfil;
        $perfil->name = $datosValidados['name'];
        $perfil->email = $datosValidados['email'];
        $perfil->password = bcrypt($datosValidados['password']);
      
        $perfil->save();
        

        // Crear el autor asociado al perfil.
        $autor = new Autor;
        $autor->perfil_id = $perfil->id;
        $autor->biografia = $datosValidados['biografia'];
        $autor->aprobado = false;
        
        $autor->save();


        // Redireccionar al usuario a la página de inicio con un mensaje de éxito.
        // return redirect('/')->with('mensaje', 'Su registro como autor está pendiente de revisión por parte del administrador. Se le enviarán las credenciales de acceso por correo electrónico si su registro es aprobado.');
    }

    /**
     * Muestra el panel de control del autor.
     *
     * @return \Illuminate\Http\Response
     */
    public function panelControl()
    {
        // Obtener el perfil del autor.
        // $perfil = Perfil::where('email', Auth::user()->email)->firstOrFail();

        // Obtener el autor correspondiente al perfil del autor.
        // $autor = Autor::where('id_perfil', $perfil->id)->firstOrFail();

        // Obtener los artículos del autor.
        // $articulos = $autor->articulos;

        $autor = Auth::guard('autores')->user();
        // dd($autor);
        // $nombre = $autor->perfil->name;
        // // dd($nombre);
        // return view('autores_panelControl', compact('nombre'));


        return view('autores_panelControl', [
            'perfil' => $autor,
        ]);







        // if (!$autor->aprobado) {
        //     return redirect()->route('autores.aprobacionPendiente');
        // }

        // return view('autores.panelControl', [
        //     'perfil' => $perfil,
        //     'autor' => $autor,
        // ]);
    }


    public function aprobacionPendiente()
    {
        return view('auth.autor-aprobacion-pendiente');
    }



}
