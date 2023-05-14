<?php

namespace App\Http\Controllers;

use App\Models\Autor;
use App\Models\Autor_Sin_Cuenta;
use App\Models\Editorial;
use App\Models\Genero;
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
        // Verifica si hay un valor almacenado en la sesión para la pestaña actual
        // Si no hay un valor almacenado, establece la pestaña por defecto en 'libros'
        if (!session('tab')) {
            session()->put('tab', 'libros');
        }
        $perfil = Auth::guard('administradores')->user();
       
        $libros= $this->mostrarLibros();
        $autoresSinCuenta= $this->mostrarAutorSinCuenta();
        $solicitudesLibros= $this->solicitudAlta();
        $solicitudesAutores= $this->solicitudLibro();
        $editoriales= Editorial::all();
        $generos=Genero::all();
        
        return view('auth.administradores_panelControl', compact('libros', 'editoriales', 'autoresSinCuenta','generos','perfil'))->with('tab', 'libros');  
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
             'generos' => 'required|array|min:1',
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
            return redirect()->back()->with(['error-libros' => 'No se encontraron autores válidos en la base de datos.'])->withInput()->with('tab', 'libros');
        }
        
         $libro = new Libro();
         $libro->isbn = $request->input('isbn');
         $libro->titulo = $request->input('titulo');
         $libro->portada = $request->input('portada');
         $libro->fecha_publicacion = $request->input('fechaPublicacion');
         $libro->editorial_id = $request->input('editorial');
         $libro->numero_paginas = $request->input('numeroPaginas');

         $libro->save();

         $libro->autorSinCuenta()->attach($autores_validos);
         $generos = $request->input('generos');
         foreach ($generos as $genero) {
            $libro->generos()->attach($genero, ['libro_id' => $libro->id]);
        }
        
         return redirect()->back()->with('success-libros', 'Libro agregado correctamente')->with('tab', 'libros');;
     }


    public function actualizar(Request $request, $accion)
    {
        if ($accion == 'bloque') {
            $libros_seleccionados = $request->input('libros_seleccionados');
            $libros_seleccionados_array = explode(',', $libros_seleccionados[0]);
            $datos_libros = json_decode($request->input('datos_libros'), true);
            foreach ($libros_seleccionados_array as $id_libro) {
                if(!$id_libro){
                    return redirect()->back()->with('error-libros', 'Selecciona algún libro')->with('tab', 'libros');
                }else{
                    // dd($datos_libros);
                    $this->actualizarBloque( $libros_seleccionados_array,  $datos_libros);
                }
            }
            return redirect()->back()->with('success-libros', 'Los libros seleccionados han sido actualizados con exito.')->with('tab', 'libros');
        } else if ($accion == 'libro') {
            $this->actualizarLibro($request, $request->id);
            return redirect()->route('administradores.panelControl')->with('success-libros', 'El libro se ha actualizado correctamente')->with('tab', 'libros');
        }
    }

    public function actualizarLibro(Request $request, $id)
    {
            // Obtener el libro a actualizar
            $libro = Libro::findOrFail($id);

            // Obtener los datos del formulario
            $isbn = $request->input('isbn');
            $titulo =$request->input('titulo');
            $autores = $request->input('autores');
            $portada = $request->input('portada');
            $fechaPublicacion = $request->input('fechaPublicacion');
            $editorial = $request->input('editorial');
            $numeroPaginas = $request->input('numeroPaginas');
        
            // Validar que los campos requeridos estén presentes y tengan un formato válido
            $request->validate([
                'isbn' => 'required|unique:libros,isbn,'.$id.'|max:255',
                'titulo' => 'required|max:255',
                'portada' => 'required|url|max:255',
                'fechaPublicacion' => 'required|date',
                'editorial' => 'required',
                'autores' => 'required',
                'numeroPaginas'=>'required|integer|min:1',
                'generos' => 'required|array|min:1',

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
                'autores.required' => 'El campo Autores es obligatorio',
                'numeroPaginas.required' => 'El campo páginas es obligatorio',
                'generos.required' => 'Selecciona algún genero',
            ]);

            // Analizar los autores ingresados por el usuario
            $autores = explode(',', $autores);
            $autores_validos = [];
                
            foreach ($autores as $nombre_completo) {
                $nombre_completo = trim($nombre_completo);
                // Reemplazar las comas en el nombre completo por espacios
                $nombre_completo_sin_comas = str_replace(',', ' ', $nombre_completo);
                // Buscar el autor por nombre completo y validar su existencia
                $autor_valido = Autor_Sin_Cuenta::whereRaw("CONCAT(nombre, ' ', apellidos) = '". $nombre_completo_sin_comas ."'")->first();
                if ($autor_valido) {
                    $autores_validos[] = $autor_valido->id;
                } else{
                    return redirect()->back()->with(['error-libros' => 'Uno de los autores no se encuentra en la base de datos.'])->withInput()->with('tab', 'libros');
                }
            }

            // dd($autores_validos);
                
            if (empty($autores_validos)) {
                return redirect()->back()->with(['error-libros' => 'No se encontraron autores válidos en la base de datos.'])->withInput()->with('tab', 'libros');
            }

            // Actualizar los datos del libro en la base de datos
            $libro->isbn = $isbn;
            $libro->titulo = $titulo;
            $libro->portada = $portada;
            $libro->fecha_publicacion = $fechaPublicacion;
            $libro->editorial_id = $editorial;
            $libro->autorSinCuenta()->sync($autores_validos);
            $libro->numero_paginas = $numeroPaginas;
            $libro->generos()->sync($request->generos);
           
            $libro->save();
        }
    
    public function actualizarBloque($libros_seleccionados_array, $datos_libros)
    {
        foreach ($libros_seleccionados_array as $key => $id_libro) {
            if(!$id_libro){
                return redirect()->back()->with('error-libros', 'Selecciona algún libro');
            }else{
                $nueva_request = new Request($datos_libros[$key]);
                
                $this->actualizarLibro($nueva_request, $id_libro);
                // return redirect()->back()->with('success-libros', 'Los libros seleccionados han sido actualizados.')->with('tab', 'libros');
            }
        }      
   }
    
        // Funcion que borra un Libro
    public function eliminarLibro($id)
    {
        $libro = Libro::findOrFail($id);
        $libro->delete();

        return redirect()->back()->with('success-libros', 'Libro eliminado correctamente')->with('tab', 'libros');
    }
    
    public function eliminar(Request $request,$accion)
    {
        if ($accion == 'bloque') {
            $libros_seleccionados = $request->input('libros_seleccionados');
            $libros_seleccionados_array = explode(',', $libros_seleccionados[0]);
            foreach ($libros_seleccionados_array as $id_libro) {
                if(!$id_libro){
                    return redirect()->back()->with('error-libros', 'Selecciona algún libro');
                }else{
                    // dd($id_libro);
                    $this->eliminarLibro($id_libro);
                }
            }
            return redirect()->back()->with('success-libros', 'Los libros seleccionados han sido borrados con exito.')->with('tab', 'libros');
        } else if ($accion == 'libro') {
            // dd($request->idLibro);
            $this->eliminarLibro($request->idLibro);
            session(['tab' => 'autores']);
            return redirect()->back()->with('success-libros', 'El libro ha sido borrado con exito.')->with('tab', 'libros');
        }
    }






      // Funcion que muestra a los datos de todos los autores sin cuenta registrados
      public function mostrarAutorSinCuenta(){
        if(Auth::guard('administradores')->user()){
            $autoresSinCuenta= Autor_Sin_Cuenta::all();
            return $autoresSinCuenta;
        }
    }

   // Funcion que registra en la base de datos a un autor sin cuenta registrada
    public function agregarAutorSinCuenta(Request $request){
            $request->validate([
                'nombre' => 'required|string',
                'apellidos' => 'required|string'
            ]);

            $autor = new Autor_Sin_Cuenta();
            $autor->nombre = $request->input('nombre');
            $autor->apellidos = $request->input('apellidos');
            $autor->save();
            return redirect()->back()->with('success-autores', 'Autor sin cuenta agregado correctamente')->with('tab', 'autores');
        }

    // Funcion que borra de la base de datos a un autor sin cuenta
    public function eliminarAutorSinCuenta(Request $request,$accion){
        if ($accion == 'bloque') {
            $autoresSinCuenta_seleccionados = $request->input('autoresSinCuenta_seleccionados');
           
            $autoresSinCuenta_seleccionados_array = explode(',', $autoresSinCuenta_seleccionados[0]);
           
            $datos_autoresSinCuenta = json_decode($request->input('datos_autoresSinCuenta'), true);
            foreach ($autoresSinCuenta_seleccionados_array as $id_autorSinCuenta) {
                
                if(!$id_autorSinCuenta){
                    return redirect()->back()->with('error-autores', 'Selecciona algún autor')->with('tab', 'autores');;
                }else{
                    // dd($request, $request->id);
                    $this->eliminarAutorSinCuentaId($request->id);
                }
            }
            return redirect()->back()->with('success-autores', 'Los libros seleccionados han sido actualizados con exito.')->with('tab', 'autores');
        } else if ($accion == 'autor') {
            $this->eliminarAutorSinCuentaId($request->id);
            return redirect()->back()->with('success-autores', 'El autor ha sido actualizado.')->with('tab', 'autores');
        }
    }

    public function eliminarAutorSinCuentaId ($id){
        $autores = Autor_Sin_Cuenta::findOrFail($id);
        $autores->delete();
    }

    public function actualizarAutorSinCuenta(Request $request, $accion)
    {
        if ($accion == 'bloque') {
            $autoresSinCuenta_seleccionados = $request->input('autoresSinCuenta_seleccionados');
            $autoresSinCuenta_seleccionados_array = explode(',', $autoresSinCuenta_seleccionados[0]);
            $datos_autoresSinCuenta = json_decode($request->input('datos_autoresSinCuenta'), true);
            foreach ($autoresSinCuenta_seleccionados_array as $id_autorSinCuenta) {
                if(!$id_autorSinCuenta){
                    return redirect()->back()->with('error-autores', 'Selecciona algún autor')->with('tab', 'autores');;
                }else{
                    // dd($id_autorSinCuenta);
                    $this->actualizarAutorSinCuentaBloque( $autoresSinCuenta_seleccionados_array,  $datos_autoresSinCuenta);
                }
            }
            return redirect()->back()->with('success-autores', 'Los autores seleccionados han sido actualizados con exito.')->with('tab', 'autores');
        } else if ($accion == 'autor') {
            $this->actualizarAutorSinCuentaInd($request, $request->id);
            return redirect()->back()->with('success-autores', 'El autor ha sido actualizado.')->with('tab', 'autores');
        }
    }
    
    public function actualizarAutorSinCuentaBloque($autoresSinCuenta_seleccionados_array, $datos_AutoresSinCuenta)
    {
        foreach ($autoresSinCuenta_seleccionados_array as $key => $id_autor) {
            if(!$id_autor){
                return redirect()->back()->with('error-autores', 'Selecciona algún autor')->with('tab', 'autores');;
            }else{
                $nueva_request = new Request($datos_AutoresSinCuenta[$key]);
        
                $this->actualizarAutorSinCuentaInd($nueva_request, $id_autor);

                return redirect()->back()->with('success-autores', 'Los autores seleccionados han sido actualizados.')->with('tab', 'autores');
            }
        }
   }

    // Funcion que modifica alguno de los datos de los autores sin cuenta
    public function actualizarAutorSinCuentaInd(Request $request, $id){
         // Obtener el autor sin cuenta a actualizar
        $autor = Autor_Sin_Cuenta::findOrFail($id);
     
       // Obtener los datos del formulario
       $nombre = $request->input('nombre');
       $apellidos =$request->input('apellidos');

       // Validar que los campos requeridos estén presentes y tengan un formato válido
       $request->validate([
            'nombre' => 'required|max:60',
            'apellidos' => 'required|max:120',
       ], [
           'nombre.required' => 'El campo nombre es obligatorio',
           'nombre.max' => 'El campo nombre debe tener como máximo 60 caracteres',
           'apellidos.required' => 'El campo apellidos es obligatorio',
           'apellidos.max' => 'El campo apellidos debe tener como máximo 120 caracteres',
       ]);

       // Actualizar los datos del autor en la base de datos
       $autor->nombre = $nombre;
       $autor->apellidos = $apellidos;
      
       $autor->save();

       return redirect()->route('administradores.panelControl')->with('success-autores', 'El autor se ha actualizado correctamente')->with('tab', 'autores');


    }











 
      // Funcion que muestra a los datos de todas las editoriales registrados
    public function mostrarEditorial(){
        if(Auth::guard('administradores')->user()){
            $editorial= Editorial::all();
            return $editorial;
        }
    }

   // Funcion que registra editoriales en la base de datos
    public function agregarEditorial(Request $request){
            $request->validate([
                'nombre' => 'required|string'
            ]);

            $editorial = new Editorial();
            $editorial->nombre = $request->input('nombre');
            $editorial->save();
            return redirect()->back()->with('success-editorial', 'Editorial agregada correctamente')->with('tab', 'editoriales');
    }


    // Funcion que borra de la base de datos las editoriales
    public function eliminarEditorial(Request $request,$accion){
        // dd($request , $accion);
        if ($accion == 'bloque') {
            $editoriales_seleccionados = $request->input('editoriales_seleccionados');
           
            $editoriales_seleccionados_array = explode(',', $editoriales_seleccionados[0]);
           
            // $datos_editoriales = json_decode($request->input('datos_editoriales'), true);
            foreach ($editoriales_seleccionados_array as $id_editorial) {
                
                if(!$id_editorial){
                    return redirect()->back()->with('error-editoriales', 'Selecciona alguna editorial')->with('tab', 'editoriales');;
                }else{
                    // dd($request, $request->id);
                    $this->eliminarEditorialId($request->id);
                }
            }
            return redirect()->back()->with('success-editoriales', 'Los libros seleccionados han sido actualizados con exito.')->with('tab', 'editoriales');
        } else if ($accion == 'editorial') {
            $this->eliminarEditorialId($request->id);
            return redirect()->back()->with('success-editoriales', 'La editorial ha sido actualizado.')->with('tab', 'editoriales');
        }
    }

    public function eliminarEditorialId ($id){
        $editoriales = Editorial::findOrFail($id);
        $editoriales->delete();
    }


    public function actualizarEditorial(Request $request, $accion)
    {
        // dd($request , $accion);
        if ($accion == 'bloque') {
            $editoriales_seleccionados = $request->input('editoriales_seleccionados');
            // dd( $editoriales_seleccionados);
            $editoriales_seleccionados_array = explode(',', $editoriales_seleccionados[0]);
            $datos_editoriales = json_decode($request->input('datos_editoriales'), true);
            foreach ($editoriales_seleccionados_array as $id_edirorial) {
                if(!$id_edirorial){
                    return redirect()->back()->with('error-editoriales', 'Selecciona alguna editorial')->with('tab', 'editoriales');;
                }else{
                    $this->actualizarEditorialBloque( $editoriales_seleccionados_array,  $datos_editoriales);
                }
            }
            return redirect()->back()->with('success-editoriales', 'Los editoriales seleccionados han sido actualizados con exito.')->with('tab', 'editoriales');
        } else if ($accion == 'editorial') {
            $this->actualizarEditorialInd($request, $request->id);
            return redirect()->back()->with('success-editoriales', 'La editorial ha sido actualizado.')->with('tab', 'editoriales');
        }
    }
    
    public function actualizarEditorialBloque($editoriales_seleccionados_array, $datos_editoriales)
    {
        foreach ($editoriales_seleccionados_array as $key => $id_editorial) {
            if(!$id_editorial){
                return redirect()->back()->with('error-editoriales', 'Selecciona alguna editorial')->with('tab', 'editoriales');;
            }else{
                $nueva_request = new Request($datos_editoriales[$key]);
        
                $this->actualizarEditorialInd($nueva_request, $id_editorial);

                // return redirect()->back()->with('success-editoriales', 'Los editoriales seleccionados han sido actualizados.')->with('tab', 'editoriales');
            }
        }
   }

    // Funcion que modifica alguno de los datos de los editoriales sin cuenta
    public function actualizarEditorialInd(Request $request, $id){
         // Obtener el editorial sin cuenta a actualizar
        $editorial = Editorial::findOrFail($id);
       // Obtener los datos del formulario
       $nombre = $request->input('nombre');
       // Validar que los campos requeridos estén presentes y tengan un formato válido
       $request->validate([
            'nombre' => 'required|max:60',
       ], [
           'nombre.required' => 'El campo nombre es obligatorio',
           'nombre.max' => 'El campo nombre debe tener como máximo 60 caracteres',
       ]);

       // Actualizar los datos de la editorial en la base de datos
       $editorial->nombre = $nombre;
      
       $editorial->save();
    }











 
      // Funcion que muestra a los datos de todas las editoriales registrados
      public function mostrarGenero(){
        if(Auth::guard('administradores')->user()){
            $genero= Genero::all();
            return $genero;
        }
    }

   // Funcion que registra generos en la base de datos
    public function agregarGenero(Request $request){
            $request->validate([
                'nombre' => 'required|string'
            ]);

            $genero = new Genero();
            $genero->nombre = $request->input('nombre');
            $genero->save();
            return redirect()->back()->with('success-genero', 'Genero agregada correctamente')->with('tab', 'generos');
    }


    // Funcion que borra de la base de datos las generos
    public function eliminarGenero(Request $request,$accion){
        // dd($request , $accion);
        if ($accion == 'bloque') {
            $generos_seleccionados = $request->input('generos_seleccionados');
           
            $generos_seleccionados_array = explode(',', $generos_seleccionados[0]);
           
            // $datos_generos = json_decode($request->input('datos_generos'), true);
            foreach ($generos_seleccionados_array as $id_genero) {
                
                if(!$id_genero){
                    return redirect()->back()->with('error-generos', 'Selecciona alguna genero')->with('tab', 'generos');;
                }else{
                    // dd($request, $request->id);
                    $this->eliminarGeneroId($request->id);
                }
            }
            return redirect()->back()->with('success-generos', 'Los libros seleccionados han sido actualizados con exito.')->with('tab', 'generos');
        } else if ($accion == 'genero') {
            $this->eliminarGeneroId($request->id);
            return redirect()->back()->with('success-generos', 'La genero ha sido actualizado.')->with('tab', 'generos');
        }
    }

    public function eliminarGeneroId ($id){
        $generos = Genero::findOrFail($id);
        $generos->delete();
    }


    public function actualizarGenero(Request $request, $accion)
    {
        // dd($request , $accion);
        if ($accion == 'bloque') {
            $generos_seleccionados = $request->input('generos_seleccionados');
            // dd( $generos_seleccionados);
            $generos_seleccionados_array = explode(',', $generos_seleccionados[0]);
            $datos_generos = json_decode($request->input('datos_generos'), true);
            foreach ($generos_seleccionados_array as $id_edirorial) {
                if(!$id_edirorial){
                    return redirect()->back()->with('error-generos', 'Selecciona alguna genero')->with('tab', 'generos');;
                }else{
                    $this->actualizarGeneroBloque( $generos_seleccionados_array,  $datos_generos);
                }
            }
            return redirect()->back()->with('success-generos', 'Los generos seleccionados han sido actualizados con exito.')->with('tab', 'generos');
        } else if ($accion == 'genero') {
            $this->actualizarGeneroInd($request, $request->id);
            return redirect()->back()->with('success-generos', 'La genero ha sido actualizado.')->with('tab', 'generos');
        }
    }
    
    public function actualizarGeneroBloque($generos_seleccionados_array, $datos_generos)
    {
        foreach ($generos_seleccionados_array as $key => $id_genero) {
            if(!$id_genero){
                return redirect()->back()->with('error-generos', 'Selecciona alguna genero')->with('tab', 'generos');;
            }else{
                $nueva_request = new Request($datos_generos[$key]);
        
                $this->actualizarGeneroInd($nueva_request, $id_genero);

                // return redirect()->back()->with('success-generos', 'Los generos seleccionados han sido actualizados.')->with('tab', 'generos');
            }
        }
   }

    // Funcion que modifica alguno de los datos de los generos sin cuenta
    public function actualizarGeneroInd(Request $request, $id){
         // Obtener el genero sin cuenta a actualizar
        $genero = Genero::findOrFail($id);
    
     
       // Obtener los datos del formulario
       $nombre = $request->input('nombre');

       // Validar que los campos requeridos estén presentes y tengan un formato válido
       // dd($datos_genero);
       $request->validate([
            'nombre' => 'required|max:60',
       ], [
           'nombre.required' => 'El campo nombre es obligatorio',
           'nombre.max' => 'El campo nombre debe tener como máximo 60 caracteres',
       ]);

       // Actualizar los datos de la genero en la base de datos
       $genero->nombre = $nombre;
      
       $genero->save();
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
