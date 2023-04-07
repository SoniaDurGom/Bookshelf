
@extends('layouts.app')

@section('content')

<div class="container">
    <h2 class="title-text">Recomendados </h2> {{ Breadcrumbs::render('libros.recomendaciones') }}
    <hr>
        <div class="card mb-4">
            <div class="card-body row">
                {{-- Se muestra un maximo de 50 --}}
                @foreach ($recomendaciones->shuffle()->take(50) as $libro) 
                    <div class="col-md-3 col-lg-2 mb-3">
                        <div>
                            <img class="card-img-top" src="{{ $libro->portada }}" alt="{{ $libro->titulo }}">
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

