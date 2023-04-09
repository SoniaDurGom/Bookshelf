
@extends('layouts.app')

@section('content')

{{-- Si $librerias es un array= Todas librerias, hay que recorrer las librerias y de cada libreria recorrer para sacar sus libros --}}
    @if ($librerias instanceof Illuminate\Database\Eloquent\Collection)
        <!-- Si se seleccionaron todas las librerías -->
        @foreach ($librerias as $libreria)
            {{-- <h3>{{ $libreria->nombre }}</h3> --}}
            @foreach ($libreria->lecturas as $lectura)
                <p>{{ $lectura->libro->titulo }}</p>
            @endforeach
        @endforeach
    @else
        <!-- Si se seleccionó una librería específica -->
        <h3>{{ $librerias->nombre }}</h3>
        @foreach ($librerias->lecturas as $lectura)
            <p>{{ $lectura->libro->titulo }}</p>
        @endforeach
    @endif







    <script src="{{ asset('js/addLibreriaLink.js') }}"></script>
@extends('layouts.app')

@section('content')
    @if ($librerias instanceof Illuminate\Database\Eloquent\Collection)
        <!-- Si se seleccionaron todas las librerías -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-6 col-sm-4 col-md-3 col-xl-2 m-5">
                    <aside>
                        <h2>Librerias</h2>
                        <ul>
                            <div>
                                <a class="link-libreria" href="{{ route('librerias.mostrar', 'todos') }}">Todos</a>
                            </div> 
                            @foreach($librerias as $libreria)
                            <div class="d-flex align-items-center">
                                <a class="link-libreria mr-2" href="{{ route('librerias.mostrar', $libreria->nombre) }}">{{ $libreria->nombre }}</a> 
                                @if($libreria->nombre != 'Leyendo' && $libreria->nombre != 'Leído' && $libreria->nombre != 'Quiero leer')
                                    <form class="form-inline" action="{{ route('librerias.borrarLibreria', $libreria->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-icon btn-sm btn-extra-sm" type="submit">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                           
                            @endforeach
                          
                            <hr>
                            <div>
                                <a id="agregarLibreria" class="btn btn-link">Añadir libreria</a>
                                <form id="formNuevaLibreria" action="{{ route('librerias.crearLibreria') }}" method="POST" style="display: none;">
                                    @csrf
                                    <label for="nuevaLibreria">Titulo:</label> <br>
                                    <input type="text" name="nuevaLibreria" id="nuevaLibreria"> <br><br>
                                    <button class="btn btn-primary" type="submit">Nueva libreria</button>
                                </form>
                            </div>
                        </ul>
                    </aside>
                </div>
                {{-- Card de todos los libros de todas las librerias --}}
                <div class="col-12 col-lg-7 col-xl-8 ml-5">
                    <div class="card mb-4">
                        <div class="row">
                            @foreach ($librerias as $libreria)
                                {{-- <h3>{{ $libreria->nombre }}</h3> --}}
                                @foreach ($libreria->lecturas as $lectura)
                                        <div class="col-6 col-sm-3 col-lg-2 mb-3">
                                            <div>
                                                {{-- !Cambiar a ficha lectura--}}
                                                <a href="{{ route('libros.fichaLibro', $lectura->libro->id) }}"> <img class="card-img-top" src="{{ $lectura->libro->portada }}" alt="{{ $lectura->libro->titulo }}"></a> 
                                            </div>
                                        </div>
                                @endforeach
                            @endforeach
                        </div>
                    </div>
                </div>         
            </div>
        </div>
                  
    @else
    {{-- {{dd("")}} --}}
    <div class="container-fluid">
        <div class="row">
            <div class="col-6 col-sm-4 col-md-3 col-xl-2 m-5">
                <aside>
                    <h2>Librerias</h2>
                    <ul>
                        <div>
                            <a class="link-libreria" href="{{ route('librerias.mostrar', 'todos') }}">Todos</a>
                        </div> 
                        @foreach($allLibrerias as $libreria)
                        <div class="d-flex align-items-center">
                            <a class="link-libreria mr-2" href="{{ route('librerias.mostrar', $libreria->nombre) }}">{{ $libreria->nombre }}</a> 
                       
                            @if($libreria->nombre != 'Leyendo' && $libreria->nombre != 'Leído' && $libreria->nombre != 'Quiero leer')
                            {{-- {{dd($librerias->id)}} --}}
                                <form class="form-inline" action="{{ route('librerias.borrarLibreria', $libreria->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-icon btn-sm btn-extra-sm" type="submit">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                       
                        @endforeach
                        
                        <hr>
                        <div>
                            <a id="agregarLibreria" class="btn btn-link">Añadir libreria</a>
                            <form id="formNuevaLibreria" action="{{ route('librerias.crearLibreria') }}" method="POST" style="display: none;">
                                @csrf
                                <label for="nuevaLibreria">Titulo:</label> <br>
                                <input type="text" name="nuevaLibreria" id="nuevaLibreria"> <br><br>
                                <button class="btn btn-primary" type="submit">Nueva libreria</button>
                            </form>
                        </div>
                        
                       
                        
                    </ul>
                </aside>
            </div>
            {{-- Card de todos los libros de todas las librerias --}}
            <div class="col-12 col-lg-7 col-xl-8 ml-5">
                <div class="card mb-4">
                    <div class="row">
                        @if (count($librerias->lecturas) == 0)
                            <div class="text-center">
                                <p>Esta libreria aún no tiene libros <a class="link" href="{{route('libros.index')}}"> Explorar</a> </p>
                            </div>
                        @endif
            
                        @foreach ($librerias->lecturas as $lectura)
                        <div class="col-6 col-sm-3 col-lg-2 mb-3">
                            <div>
                                {{-- !Cambiar a ficha lectura--}}
                                <a href="{{ route('libros.fichaLibro', $lectura->libro->id) }}"> <img class="card-img-top" src="{{ $lectura->libro->portada }}" alt="{{ $lectura->libro->titulo }}"></a> 
                            </div>
                        </div>
                    @endforeach 
                    </div>
                </div>
            </div>         
        </div>
    </div>

    @endif

@endsection







@endsection

