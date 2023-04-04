<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Bookshelf</title>
    <link rel="shortcut icon" href="{{ asset('favicons/favicon.ico') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    @extends('layouts.app')

    @section('content')
        <div class="container">
            <div class="row justify-content-center">
                <div class="col col-md-10">
                    <div class="card">
                        <div class="card-header">{{ __('Perfil de lector') }} </div>

                        <div class="card-body">
                            @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif

                            <div class= "row">
                                <div class= "col-4 col-xl-3">
                                    <!-- foto -->
                                    <div class="avatar">
                                        @if ($perfil->foto)
                                            <img src="{{ asset($perfil->foto) }}" alt="Foto de perfil">
                                        @else
                                            <img src="{{ asset('img/default-profile.png') }}" alt="Foto de perfil">
                                        @endif

                                    </div>
                                
                                    {{-- <br>

                                    <form action="/perfil/subir-foto" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <input type="file" name="foto"> <br><br>
                                        <button class="btn btn-primary" type="submit">Subir</button>
                                    </form> --}}

                                </div>

                                <br>

                                <div class= "col-8 col-xl-5">
                                    <span class="texto_campos_panelControl"> Nombre: </span> {{ $perfil->perfil->name }} </span> <br>
                                    <span class="texto_campos_panelControl">Miembro desde:</span>  {{ $perfil->perfil->created_at->format('d/m/Y') }} </span>
                                    {{-- <form action="{{ route('lectores.borrarCuenta') }}" method="POST" onsubmit="return confirm('¿Estás seguro de que quieres eliminar tu cuenta?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-danger boton-rojo"> Borrar cuenta </button>
                                        <wbr>
                                    </form> --}}
                                    <br>
                                    <a class="link" href="{{ route('lectores.formulario.ajustes') }}">(editar perfil)</a>

                                   
                                    
                                </div>


                            </div>

                            <wbr>
                            
                            <div class="row">
                                 {{-- Libros favoritos --}}
                                <h5>Libros favoritos</h5>
                                <hr>
                               
                               
                                
                            </div>

                            <wbr>
                            
                            <div class="row">
                                 {{-- Librerias --}}
                                <h5>Librerias</h5>
                                <hr>
                                   
                    
                                    
                            </div>
                            <wbr>
                            
                            <div class="row">
                                {{-- Leyendo actualmente --}}
                                <h5>Libros favoritos</h5>
                                <hr>
                                   
                                   
                                    
                            </div>
    
                            <wbr>
                                
                            <div class="row">
                             {{-- Generos favoritos --}}
                                <h5>Generos favoritos</h5>
                                <hr>
                                       
                        
                                        
                            </div>




                               


                            





                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    
</body>
</html>