@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-6 col-sm-4 col-md-3 col-xl-2 m-5">
            <aside>
                <h2>Librerias</h2>
                <ul>
                    <div>
                        <a class="link-libreria" href="{{ route('librerias.libros', 'todos') }}">Todos</a>
                    </div> 
                    @foreach($librerias as $libreria)
                    
                    <div class="d-flex align-items-center">
                        <a class="link-libreria mr-2" href="{{ route('librerias.libros', $libreria->nombre) }}">{{ $libreria->nombre }}</a> 
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
                        <form action="{{ route('librerias.crearLibreria') }}" method="POST">
                            @csrf
                            <label for="nuevaLibreria">Libreria:</label> <br>
                            <input type="text" name="nuevaLibreria" id=""> <br><br>
                            <button class="btn btn-primary" type="submit">Nueva libreria</button>
                        </form>
                    </div> 
                </ul>
            </aside>
        </div>


        <div class="col-12 col-lg-7 col-xl-9 ml-5">
            <!-- Contenido principal de la página -->
            @foreach($lecturas as $lectura)
                <div class="card mb-4">
                    <div class="row">
                        <div class="col-6 col-sm-3 col-lg-2 mb-3">
                            <div>
                                {{-- ! --}}
                                <a href="{{ route('libros.fichaLibro', $lectura->libro->id) }}"> <img class="card-img-top" src="{{ $lectura->libro->portada }}" alt="{{ $lectura->libro->titulo }}"></a>
                                
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                    <!-- otros detalles de la lectura -->
                
            @endforeach
        </div>
    </div>
</div>





@endsection
