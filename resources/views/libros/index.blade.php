@extends('layouts.app')

@section('content')

<div class="container">
    @foreach ($generos as $genero)
        <div class="card mb-4">
            <div class="card-header">
                <h2>{{ $genero->nombre }}</h2>
            </div>
            <div class="card-body row">
                @foreach ($genero->libros->shuffle()->take(6) as $libro)
                    <div class="col-md-3 col-lg-2 mb-3">
                        <div>
                            <img class="card-img-top" src="{{ $libro->portada }}" alt="{{ $libro->titulo }}">
                            <div class="card-body">
                                <h6 class="card-title" style="height: 60px; overflow: hidden;">{{ $libro->titulo }}</h6>
                                <div class="text-center">
                                    <a href="" class="btn btn-primary" >Ver más</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="card-footer">
                <a href="{{ route('genero.index', $genero->nombre) }}" class="btn btn-link">Ver todos los libros de {{ $genero->nombre }}</a>
            </div>
        </div>
    @endforeach
</div>



@endsection
