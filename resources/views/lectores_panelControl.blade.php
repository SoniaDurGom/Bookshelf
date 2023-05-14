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
                                    <h5>Libros favoritos</h5>
                                    <hr>
                                    @foreach($librosFavoritos as $libro)
                                        <div class="col-6 col-sm-4 col-md-3 col-lg-2 mb-3">
                                            <a href="{{ route('libros.fichaLibro', $libro->id) }}">
                                                <img class="card-img-top" src="{{ asset($libro->portada) }}" alt="Portada de {{ $libro->titulo }}">
                                            </a>  
                                        </div>
                                    @endforeach
                                    <div class="col-12 text-center">
                                        <a href="{{ route('librerias.mostrar', 'todos') }}"class="btn btn-primary">Ver más</a>
                                    </div>
                                </div>
                                

                            <wbr>
                            
                                <div class="row justify-content-center">
                                    <h5>Librerías</h5>
                                    <hr>
                                    @foreach($libreriasUsuario as $libreria)
                                        <div class="col-6 col-md-3 col-lg-2 col-lg-2 mb-3">
                                            <div class="card-body p-2">
                                                    <a href="{{ route('librerias.mostrar', $libreria->nombre) }}" class="card-title">{{ $libreria->nombre }}</a>
                                                    <p class="card-text">{{ count($libreria->lecturas) }} lecturas</p>
                                            </div>
                                        </div>
                                    @endforeach
                                    <div class="col-12 text-center">
                                        <a href="{{ route('librerias.mostrar', 'todos') }}" class="btn btn-primary">Ver más</a>
                                    </div>
                                </div>
                                
                            <wbr>
                        

                            <div class="row">
                                <h5>Leyendo actualmente</h5>
                                <hr>
                                @foreach($lecturasLeyendo as $lectura)
                                    <div class="col-xl-4 col-lg-5">
                                        <div class="mb-3">
                                            <div class="row no-gutters">
                                                <div class="col-4">
                                                    <a href="{{ route('libros.fichaLibro', $lectura->libro->id) }}"> 
                                                        <img class="card-img-top" src="{{ $lectura->libro->portada }}" alt="{{ $lectura->libro->titulo }}">
                                                    </a> 
                                                </div>
                                                <div class="col-8">
                                                    <div class="card-body">
                                                        <a class="txt-link" href="{{ route('libros.fichaLibro', $lectura->libro->id) }}"> 
                                                            <h5 class="card-title">{{ $lectura->libro->titulo }}</h5>
                                                        </a> 
                                                        <div class="progress">
                                                            <div class="progress-bar progress-bar-custom" role="progressbar" style="width: {{ $lectura->paginas_leidas / $lectura->libro->numero_paginas * 100 }}%;" aria-valuenow="{{ $lectura->paginas_leidas }}" aria-valuemin="0" aria-valuemax="{{ $lectura->libro->numero_paginas }}"></div>
                                                        </div>
                                                      <p class="text-center mb-0">{{ $lectura->paginas_leidas }} / {{ $lectura->libro->numero_paginas }} páginas</p>
                                                              
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>






    
                            <wbr>
                                
                            <div class="row">
                             {{-- Generos favoritos --}}
                                <h5>Generos favoritos</h5>
                                <hr>
                                {{-- Mostrar todos los generos de la tabla generos, que se marquen en azul los que son actualmente los favoritos --}}
                                {{-- Permitir marcar 3 --}}
                                {{-- Boton guardar --}}
                                <form action="{{ route('generos.guardar-favoritos') }}" method="POST">
                                    @csrf
                                    <div class="row row-cols-3 row-cols-md-6">
                                      @foreach($generos as $index => $genero)
                                        {{-- @if($index % 6 === 0)
                                          </div><div class="row row-cols-3 row-cols-md-6">
                                        @endif --}}
                                        <div class="col">
                                            <input type="checkbox" name="favoritos[{{ $genero->id }}]" value="{{ $genero->id }}" @if($perfil->generosFavoritos->contains($genero->id)) checked @endif>
                                            <label>{{ $genero->nombre }}</label>
                                        </div>
                                      @endforeach
                                    </div>

                                    @if(session('success'))
                                        <div class="alert alert-success">
                                            {{ session('success') }}
                                        </div>
                                    @endif

                                    @if($errors->any())
                                        <div class="alert alert-danger">
                                            @foreach($errors->all() as $error)
                                                <p>{{ $error }}</p>
                                            @endforeach
                                        </div>
                                    @endif



                                    <button class="btn btn-primary" type="submit">Guardar</button>
                                  </form>

                                  
                               

                                       
                        
                                        
                            </div>




                               


                            





                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    
</body>
</html>