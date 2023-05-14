@extends('layouts.app')

@section('content')



<div class="container">
    <h2 class="title-text">{{ $genero->nombre }} </h2> {{ Breadcrumbs::render('libro.porGenero', $genero) }}
    <hr>
        <div class="card mb-4">
            <div class="card-body row">
                {{-- Se muestra un maximo de 20 --}}
                @foreach ($genero->libros->shuffle()->take(20) as $libro) 
                    <div class="col-sm-4 col-md-3 col-lg-2 mb-3">
                        <div>
                            <img class="book-card__image " src="{{ $libro->portada }}" alt="{{ $libro->titulo }}">
                            <div class="card-body">
                                {{-- <h6 class="card-title" style="height: 60px; overflow: hidden;">{{ $libro->titulo }}</h6> --}}
                                <div class="text-center">
                                    
                                    <a href="{{ route('libros.fichaLibro', $libro->id) }}" class="btn btn-primary">Ver más</a>


                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
        </div>
  
</div>



@endsection
