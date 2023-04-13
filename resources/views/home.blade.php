<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Bookshelf</title>
    <link rel="shortcut icon" href="{{ asset('favicons/favicon.ico') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="{{ asset('js/btnReto.js') }}"></script>
</head>
<body>
    @extends('layouts.app')

    @section('content')

        <div class="container">
            <div class="row">
                <div class="col-xl-4 col-md-6 d-flex flex-column">
                    {{-- Izquierda --}}
                    <h5>Leyendo</h5>
                        @if (count($lecturasLeyendo) == 0)
                            <div>
                                <a class="link" href="{{route('libros.index')}}"> Explorar</a> 
                            </div>
                        @endif
                        @foreach ($lecturasLeyendo as $lectura)
                            {{-- {{ dd($lectura->libro->titulo) }} --}}
                            <div class="col-11 mb-2 d-flex align-items-start">
                                    {{-- !Cambiar a ficha lectura--}}
                                    <a href="{{ route('libros.fichaLibro', $lectura->libro->id) }}"> <img class="card-img-top" src="{{ $lectura->libro->portada }}" alt="{{ $lectura->libro->titulo }}"></a> 
                                    <div class=" col-9 m-2">
                                        <h5 class="card-title small">{{$lectura->libro->titulo}}</h5>
                                        @foreach($lectura->libro->autorSinCuenta as $autor)
                                            @foreach($autores_con_perfil as $autor_con_perfil)
                                                @if($autor->nombreCompleto == $autor_con_perfil->perfil->name)
                                                    <span class="link_autor small">
                                                        <a class="link" href="#">
                                                            {{$autor_con_perfil->perfil->name}}
                                                        </a> 
                                                    </span>
                                                    <br>
                                                    @break
                                                @else
                                                    <span class="link_autor small">{{ $autor->nombre }} {{ $autor->apellidos }} </span> 
                                                    <br>
                                                @endif
                                            @endforeach
                                        @endforeach
                               
                                </div>

                            </div>
                        @endforeach 

                        <div class="col-9 col-sm-8 ">
                            <hr>
                            <h5>Pendientes</h5>
                            @if (count($lecturasPendientes) == 0)
                                <div>
                                    <a class="link" href="{{route('libros.index')}}"> Explorar</a> 
                                </div>
                            @endif
                            <div class="row">
                                @foreach ($lecturasPendientes as $lectura)
                                    <div class="col-4 mb-1 p-1">
                                        {{-- !Cambiar a ficha lectura--}}
                                        <a href="{{ route('libros.fichaLibro', $lectura->libro->id) }}"> <img class="card-img-top" src="{{ $lectura->libro->portada }}" alt="{{ $lectura->libro->titulo }}"></a> 
                                    </div>
                                @endforeach 
                            </div>
                        </div>
                        
                        
                    
                    <div class="col-9 col-sm-8 mb-1">
                        <hr>
                        <h5>Librerias</h5>
                        <div>
                            <a class="link-libreria" href="{{ route('librerias.mostrar', 'todos') }}">
                                ({{$num_total_lecturas}}) Todos
                            </a>
                        </div> 
                        @foreach($librerias as $libreria)
                            <div class="d-flex align-items-center">
                                <a class="link-libreria mr-2" href="{{ route('librerias.mostrar', $libreria->nombre) }}"> 
                                    ({{$numero_de_lecturas_por_libreria[$libreria->id]}}) {{ $libreria->nombre }}
                                </a> 
                            </div>
                            
                           
                        @endforeach
                        <br>
                    </div>
                </div>

               


                {{-- Centro: ESPACIO RESERVADO PARA TABLO DE NOTICIAS --}}
                <div class="col-xl-4 d-none d-xl-block">
                    <img class="img-index" src="{{ asset('img/index_img.jpeg') }}" alt="Foto central" >
                </div>
                
                <hr class=" col-9 col-sm-8 d-block d-md-none">

                
                <div class="col-xl-4 col-md-6">
                    {{-- Derecha --}}
                    <h5>Recomendaciones</h5>
                    <div id="carouselLibrosRecomendados" class="carousel slide" data-ride="carousel">
                        <div class="carousel-inner">
                            @foreach($libros_recomendados as $index => $libro)
                                <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                    <div class="d-flex align-items-start">
                                        <img id=img-recomendacion-index class="card-img-top mr-3" src="{{ $libro->portada }}" alt="{{ $libro->titulo }}">
                                        <div class="m-2">
                                            <h5 class="card-title">{{ $libro->titulo }}</h5>
                                            @foreach($libro->autorSinCuenta as $autor)
                                                @foreach($autores_con_perfil as $autor_con_perfil)
                                                    @if($autor->nombreCompleto == $autor_con_perfil->perfil->name)
                                                        <p class="link_autor">
                                                            de <a class="link" href="">
                                                                {{$autor_con_perfil->perfil->name}}
                                                            </a> 
                                                        </p>
                                                        @break
                                                    @else
                                                        <p class="link_autor">de {{ $autor->apellidos }}, {{ $autor->nombre }}</p>
                                                    @endif
                                                @endforeach
                                            @endforeach
                                            
                                            <a href="{{ route('libros.fichaLibro', $libro->id) }}" class="btn btn-primary">Añadir</a> <br>
    
                                        </div>
                                    </div>
                                    <div>
                                        <p>
                                            @if (str_word_count($libro->sinopsis) <= 50)
                                              {{ $libro->sinopsis }}
                                              @else
                                              {{ substr($libro->sinopsis, 0, strpos($libro->sinopsis, ' ', 200)) }}...
                                              <a class="link" href="{{ route('libros.fichaLibro', $libro->id) }}" class="btn btn-primary">Continuar leyendo</a>
                                            @endif
                                          </p>
                                        <wbr>
                                    </div>
                                       
                                            
                                           
                                        
                                </div>
                            @endforeach
                        </div>
                    
                        <a class="carousel-control-next" href="#carouselLibrosRecomendados" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                    

                    <div class="col-9 col-sm-8">
                        <hr>
                        <h5>{{$reto->anio}} Reto de lectura</h5>
                         {{-- Imagen del reto anual, que se guarda en public/img --}}
                        {{-- <img id="img-reto" src="{{ asset('img/reto2023.jpeg') }}" alt="imagen reto literario año 2023"> --}}

                        @if ($reto->libros_objetivo == 0)
                            {{-- Si no se ha empezado un reto, mostrar para marcar el objetivo de libros --}}
                            {{-- Mostrar formulario para marcar el objetivo de libros --}}
                            <div class='row'>
                                <div class='col-6'>
                                    <div>
                                        <img id="img-reto" src="{{ asset('img/reto2023.jpeg') }}" alt="imagen reto literario año 2023">
                                    </div>
                                </div>
                                <div class="col-5">
                                    <form action="{{ route('reto.marcarObjetivo') }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <label for="libros_objetivo">Objetivo:</label>
                                            <input type="number" name="libros_objetivo" id="libros_objetivo" class="form-control" min="1">
                                        </div>
                                        <br>
                                        
                                        <button type="submit" class="btn btn-primary" id="guardarBtn" disabled>Guardar</button>
                                    </form>
                                   
                                    
                                    
                                </div>
                            </div>
                            
                        @else
                            {{-- Si se ha empezado mostrar libros leidos de / objetivo --}}
                            {{-- Mostrar libros leidos de / objetivo --}}
                            <div class='row'>
                                <div class='col-6'>
                                    <div>
                                        <img id="img-reto" src="{{ asset('img/retoSinFondo.png') }}" alt="imagen reto literario año 2023">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <br>
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-custom" role="progressbar" style="width: {{ $reto->libros_leidos / $reto->libros_objetivo * 100 }}%;" aria-valuenow="{{ $reto->libros_leidos}}" aria-valuemin="0" aria-valuemax="{{ $reto->libros_objetivo  }}"></div>
                                    </div>

                                    {{-- <progress value="{{ $reto->libros_leidos }}" max="{{ $reto->libros_objetivo }}"></progress> --}}
                                    <span>{{ $reto->libros_leidos }}/{{ $reto->libros_objetivo }} libros leidos</span> <br>
                                    <a class="link" href="{{ route("reto.mostrar") }}"> Ver progreso</a>
                                    
                                </div>
                            </div>






                            
                           
                        
                        @endif

                    
                       
                       
                       
                    </div>
                    


                </div>

            </div>
        </div>




        
    @endsection

    
</body>
</html>