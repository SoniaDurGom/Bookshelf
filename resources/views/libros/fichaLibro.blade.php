<script src="{{ asset('js/starReview.js') }}"></script>
@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="m-5">
            <div class="row">
                <div class="col-md-3 d-flex flex-column justify-content-center align-items-center">
                    <img src="{{ $libro->portada }}" class="portada_libro_ficha" alt="{{ $libro->titulo }}">
                    <wbr>
                    <div>
                        {{--TODO: Despliega las librerias --}}
                        <button class="btn btn-primary" >Añadir a libreria</button> 
                    </div>
                    
                </div>
                <div class="col-md-9">
                    <div class="card-body">
                        <h1 class="card-title">{{ $libro->titulo }}</h1>
                        <hr>
                         {{--Si el autor esta registrad= nombre y apellidos coincide con Name de Autor: Mostrar la foto, la biografia y enlace a su pagina de autor --}}
                        <div class="row">
                            <div class="col-6">
                                {{-- Autor/es --}}
                                @foreach($libro->autorSinCuenta as $autor)
                                    <h5 class="card-text link_autor">  {{ $autor->nombre }} {{ $autor->apellidos }} </h5>
                                @endforeach

                                {{-- Generos , buble--}}
                                <br>
                                <span>Generos: </span>
                                @foreach($libro->generos as $genero)
                                    <a class="card-text link" href="{{ route('genero.index', $genero->nombre)}}"> {{ $genero->nombre }} </a>
                                  
                                @endforeach
                                <br><br>
                                <span>Edición: </span>
                                <div class="m-4">
                                    <span class="card-text">Paginas: {{ $libro->numero_paginas }} </span>  <br>
                                    <span class="card-text">Fecha de publicacion: {{ $libro->fecha_publicacion}} </span> <br>
                                    <span class="card-text">ISBN: {{ $libro->isbn }}</span><br>
                                    <span class="card-text">Editorial: {{ $libro->editorial->nombre }}</span><br>
    
                                </div>
                               
                                {{-- Estrellas y nota media --}}
                                <br>
                                <div class="card-text">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= $media)
                                            <i class="fas fa-star text-warning"></i>
                                        @elseif ($i - 0.5 <= $media)
                                            <i class="fas fa-star-half-alt text-warning"></i>
                                        @else
                                            <i class="far fa-star text-warning"></i>
                                        @endif
                                    @endfor
                                
                                    @if ($media)
                                        <span class="ml-2">{{ $media }}/5 </span>
                                        <span class="valoraciones_txt_libro">({{count($libro->valoraciones)}} valoraciones)</span>
                                    @else
                                        <span class="ml-2">Este libro aún no ha sido valorado</span>
                                        <br><br>
                                    @endif
                                    
                                    <br> <br>

                                </div>
                                
                            </div>

                            {{--Si autor del libro tiene cuenta registrada se manda a la pagina de autor
                                Donde Lectores podrán dejar preguntas en un tablón  --}}
                        
                            <div class="col-12 col-md-6">
                                {{-- {{dd($autorRegistrado)}} --}}
                                @if ($autorRegistrado!=null)
                                    <div class="card">
                                        <div class="row">
                                            <div class="col-7 col-md-5 col-lg-4 col-xl-3">
                                                <div class="avatar">
                                                    @if ($autorRegistrado->foto!=null)
                                                        <img src="{{ asset($autorRegistrado->perfil->foto) }}" alt="Foto de perfil">
                                                    @else
                                                
                                                        <img src="{{ asset('img/default-profile.png') }}" alt="Foto de perfil">
                                                    @endif
            
                                                </div>
                                                
                                            </div>
                                            <div class="col-12 col-md-9 col-xl-7 col-xl-6 m-2">
                                                <h3 class="card-text">
                                                    {{$autorRegistrado->perfil->name}}
                                                </h3>
                                            </div>
                                        </div>
                                        <wbr>
                                        <div class="row">
                                            <div class="col-12">
                                                <span>{{$autorRegistrado->biografia}}</span> <a class="link">Ver más</a> {{--! Enlace no enlazado--}}
                                            </div>
                                        </div>
                                       
                                    </div>
                                    
                                @endif
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <wbr>

                {{--Todo: Ficha de lectura del usuario Lector --}}
                {{-- Fecha de inicio, páginas leidas, fecha de fin (), estado de la lectura (cambio de estado cuando las páginas leidas ), 
                    ¿notas hasta 1500 caracteres? , libreria en la que está--}}




                <hr>
                <div class="row">
                    <div class="col-12 mb-4">
                        <h3>Mi valoración</h3>
                    </div>
                    <div class="col-4 col-md-2 d-flex flex-column justify-content-center align-items-center">
                        <div class="avatar">
                            <img src="{{ asset($perfil->perfil->foto ?? 'img/default-profile.png') }}" alt="Foto de perfil de {{ $perfil->perfil->name }}">
                        </div>
                        <p>{{ $perfil->perfil->name }}</p>
                    </div>
                    <div class="col-9">
                        <form action="{{ route('valoracion.guardar') }}" method="POST">
                            @csrf
                            <input type="hidden" name="libro_id" value="{{ $libro->id }}">
                            <div id="stars">
                                <i class="far fa-star fa-2x text-warning" name="puntuacion" data-value="1"></i>
                                <i class="far fa-star fa-2x text-warning" name="puntuacion" data-value="2"></i>
                                <i class="far fa-star fa-2x text-warning" name="puntuacion" data-value="3"></i>
                                <i class="far fa-star fa-2x text-warning" name="puntuacion" data-value="4"></i>
                                <i class="far fa-star fa-2x text-warning" name="puntuacion" data-value="5"></i>
                            </div>
                            <input type="hidden" name="puntuacion" id="puntuacion" value=""> 
                            <wbr>
                            @if(session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif

                         
                            <textarea class="form-control" name="comentario" cols="100" rows="8" placeholder="Comenta tu lectura..."></textarea> <br> 
                            
                            @if ($errors->has('unique'))
                                <div class="alert alert-danger">{{ $errors->first('unique') }}</div>
                            @endif
                            <button type="submit" class="btn btn-primary" id="btn-valorar">Valorar</button>
                           
                        </form>
                        
                    </div>
                </div>
            
                <wbr>

                {{-- TABLON --}}
            <div class="row">
                <div class="col-12 col-md-9 mb-4 mx-auto">
                    <h2>Reseñas:</h2>
                    <hr>
                    <div class="row">
                        <div class="col-3 d-none d-md-block">
                           
                        </div>

                        <div class="col-9">
                            {{-- si ha sido valorado, dejar escribir una valoracion: Text area, puntuacion y boton enviar --}}
                            {{-- Foreach de todas las valoraciones de todos los usuarios de este libro --}}
                           
                           
                            @foreach ($libro->valoraciones as $valoracion)
                                <div class="row">
                                     {{-- A la izquierda, Foto y debajo nombre del lector que hace la review --}}
                                    <div class="col-6 col-md-2 d-flex flex-column justify-content-center align-items-center">
                                        <div class="avatar">
                                            @if ($valoracion->lector->foto!=null)
                                                <img src="{{ asset($valoracion->lector->foto) }}"alt="Foto de perfil de {{$valoracion->lector->perfil->name}}">
                                            @else
                                        
                                                <img src="{{ asset('img/default-profile.png') }}" alt="Foto de perfil de {{$valoracion->lector->perfil->name}}">
                                            @endif
    
                                        </div>

                                        <p> {{$valoracion->lector->perfil->name}}</p>

                                       
                                    </div>
                                    
                                     {{-- A la derecha, arriba la puntuacion dada en estrellas y debajo Texto de la valoracion --}}
                                     <div class="col-12 col-md-7 mx-5">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= $valoracion->puntuacion)
                                                <i class="fas fa-star text-warning"></i>
                                            @elseif ($i - 0.5 <= $valoracion->puntuacion)
                                                <i class="fas fa-star-half-alt text-warning"></i>
                                            @else
                                                <i class="far fa-star text-warning"></i>
                                            @endif
                                        @endfor
                                        <br>
                                        <p> {{$valoracion->comentario}} </p>
                            
                                    </div>
                                    
                                </div>
                                
                                {{-- <hr> --}}
                            <wbr>
                           
                            @endforeach
                            
                        </div>
                    </div>
            
                </div>

            </div>

        </div>
    </div>
@endsection
