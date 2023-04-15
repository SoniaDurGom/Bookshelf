<?php

namespace App\Http\Controllers;

use App\Models\Autor;
use App\Models\Autor_Sin_Cuenta;
use App\Models\Editorial;
use App\Models\Libro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Perfil;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
        $libros= $this->mostrarLibros();
        $solicitudesLibros= $this->solicitudAlta();
        $solicitudesAutores= $this->solicitudLibro();
        $editoriales= Editorial::all();

        return view('auth.administradores_panelControl', [
            'perfil' => $administrador,
            'libros' => $libros,
            'editoriales' => $editoriales,
        ]);


        
    }



    // Funcion que saca todos los Libros de la base de datos
    public function mostrarLibros(){
        if(Auth::guard('administradores')->user()){
            $libros= Libro::all();
            return $libros;
            
        }
            

    }

     // Funcion que saca modifica los datos de un Libro
     public function agregarLibro(Request $request)
     {
         $request->validate([
             'isbn' => 'required|string|unique:libros,isbn',
             'titulo' => 'required|string',
             'autores' => 'required|string',
             'portada' => 'required|string',
             'fechaPublicacion' => 'required|date',
             'editorial' => 'required|integer',
             'numeroPaginas'=>'required|integer|min:1',
         ]);
     
         $autores = explode(',', $request->autores);
         $autores_validos = [];
         
         foreach ($autores as $autor) {
             $nombres = explode(' ', trim($autor));
             $nombre_autor = end($nombres);
             array_pop($nombres);
             $apellido_autor = implode(' ', $nombres);
             
             // Buscar el autor por nombre y apellido y validar su existencia
             $autor = Autor_Sin_Cuenta::where('nombre', $nombre_autor)->where('apellidos', $apellido_autor)->first();
             if ($autor) {
                 $autores_validos[] = $autor->id;
             }
         }

         if (empty($autores_validos)) {
            return redirect()->back()->withErrors(['autores' => 'No se encontraron autores válidos en la base de datos.'])->withInput();
        }
         
     
        //  dd( $autores_validos );

         $libro = new Libro();
         $libro->isbn = $request->input('isbn');
         $libro->titulo = $request->input('titulo');
         $libro->portada = $request->input('portada');
         $libro->fecha_publicacion = $request->input('fechaPublicacion');
         $libro->editorial_id = $request->input('editorial');
         $libro->numero_paginas = $request->input('numeroPaginas');
         $libro->save();
     
         // Agregar los autores a la tabla de pivote
         $libro->autorSinCuenta()->attach($autores_validos);
     
         return redirect()->back()->with('success', 'Libro agregado correctamente');
     }
     


    // // Funcion que saca modifica los datos de un Libro
    // public function actualizarLibros(Request $request, $id)
    // {
    //     // Obtener el libro a actualizar
    //     $libro = Libro::find($id);

    //     // Obtener los datos del formulario
    //     $isbn = $request->input('isbn');
    //     $titulo = $request->input('titulo');
    //     $autores = $request->input('autores');
    //     $portada = $request->input('portada');
    //     $fechaPublicacion = $request->input('fechaPublicacion');
    //     $editorialId = $request->input('editorial');

    //     // Validar que los campos requeridos estén presentes y tengan un formato válido
    //     $validator = Validator::make($request->all(), [
    //         'isbn' => 'required',
    //         'titulo' => 'required',
    //         'portada' => 'required',
    //         'fechaPublicacion' => 'required|date',
    //         'editorial' => 'required'
    //     ]);

    //     if ($validator->fails()) {
    //         return redirect()->back()->withErrors($validator)->withInput();
    //     }

    //     // Validar que los autores especificados en el campo "autores" estén registrados en la base de datos
    //     $autoresArray = preg_split('/[\s,]+/', $autores); // Separar la cadena de texto en un array de nombres
    //     $autoresIds = [];

    //     foreach ($autoresArray as $nombre) {
    //         $autor = Autor::where('nombre', $nombre)->first();

    //         if (!$autor) {
    //             // Si el autor no está registrado en la base de datos, mostrar un error
    //             $validator->errors()->add('autores', 'El autor ' . $nombre . ' no está registrado en la base de datos.');
    //             return redirect()->back()->withErrors($validator)->withInput();
    //         }

    //         $autoresIds[] = $autor->id;
    //     }

    //     // Actualizar los datos del libro en la base de datos
    //     $libro->isbn = $isbn;
    //     $libro->titulo = $titulo;
    //     $libro->portada = $portada;
    //     $libro->fecha_publicacion = $fechaPublicacion;
    //     $libro->editorial_id = $editorialId;
    //     $libro->autores()->sync($autoresIds);
    //     $libro->save();

    //     return redirect()->route('administradores.panelControl')->with('success', 'El libro se ha actualizado correctamente');
    // }

    public function actualizarLibro(Request $request, $id)
    {
       
        $request->validate([
            'isbn' => 'required|unique:libros,isbn,'.$id.'|max:255',
            'titulo' => 'required|max:255',
            'portada' => 'required|url|max:255',
            'fechaPublicacion' => 'required|date',
            'editorial' => 'required',
            'autores' => 'required',
            'numeroPaginas'=>'required|integer|min:1',
        ], [
            'isbn.required' => 'El campo ISBN es obligatorio',
            'isbn.unique' => 'El ISBN ya existe en la base de datos',
            'isbn.max' => 'El campo ISBN debe tener como máximo 255 caracteres',
            'titulo.required' => 'El campo Título es obligatorio',
            'titulo.max' => 'El campo Título debe tener como máximo 255 caracteres',
            'portada.required' => 'El campo Portada es obligatorio',
            'portada.url' => 'El campo Portada debe ser una URL válida',
            'portada.max' => 'El campo Portada debe tener como máximo 255 caracteres',
            'fechaPublicacion.required' => 'El campo Fecha de publicación es obligatorio',
            'fechaPublicacion.date' => 'El campo Fecha de publicación debe ser una fecha válida',
            'editorial.required' => 'El campo Editorial es obligatorio',
            'autores.required' => 'El campo Autores es obligatorio'
        ]);
        

        // Buscar la editorial por nombre y obtener su id
        // $editorial = Editorial::where('nombre', $request->editorial)->first();
        $editorial_id = $request->editorial;
    
        // Analizar los autores ingresados por el usuario
        $autores = explode(',', $request->autores);
        $autores_validos = [];
        
        foreach ($autores as $autor) {
            $nombres = explode(' ', trim($autor));
            $nombre_autor = end($nombres);
            array_pop($nombres);
            $apellido_autor = implode(' ', $nombres);
            
            // Buscar el autor por nombre y apellido y validar su existencia
            $autor = Autor_Sin_Cuenta::where('nombre', $nombre_autor)->where('apellidos', $apellido_autor)->first();
            if ($autor) {
                $autores_validos[] = $autor->id;
            }

            if (empty($autores_validos)) {
                return redirect()->back()->withErrors(['autores' => 'No se encontraron autores válidos en la base de datos.'])->withInput();
            }
        }
        
       
        // Actualizar el libro en la base de datos con los nuevos valores
        $libro = Libro::find($id);
        $libro->isbn = $request->isbn;
        $libro->titulo = $request->titulo;
        $libro->portada = $request->portada;
        $libro->fecha_publicacion = $request->fechaPublicacion;
        $libro->editorial_id = $editorial_id;
        $libro->numero_paginas = $request->input('numeroPaginas');
        $libro->autorSinCuenta()->sync($autores_validos);

        $libro->save();

    
        return redirect()->back()->with('mensaje', 'Libro actualizado correctamente');
    }
    

    


    // Funcion que borra un Libro
    public function eliminarLibro($id)
    {
        $libro = Libro::findOrFail($id);
        $libro->delete();

        return redirect()->back()->with('success', 'Libro eliminado correctamente');
    }






    // Funcion que muestra los datos de la solicitud de alta del autor
    public function solicitudAlta(){

    }

    // Funcion borra la solicitud de alta del autor
    public function eliminarSolicitudAutor ($id){

    }

    // Funcion que modifica alguno de los datos de la solicitud de perfil del autor
    public function modificarSolicitudAutor(Request $req){

    }

     // Funcion que registra al autor como perfil valido de la aplicacion
    public function registrarAutor(Request $req){

    }








    
    // Funcion que muestra los datos de la solicitud de alta del autor
    public function solicitudLibro(){

    }

    // Funcion borra la solicitud de alta del autor
    public function eliminarSolicitudLibro ($id){

    }

    // Funcion que modifica alguno de los datos de la solicitud de perfil del autor
    public function modificarSolicitudLibro(Request $req){

    }
     // Funcion que registra al autor como perfil valido de la aplicacion
    public function registrarLibro(Request $req){

    }







    


    
}
