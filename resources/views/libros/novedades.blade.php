@extends('layouts.app')

@section('content')

<div class="container">
    {{ Breadcrumbs::render('libros.novedades') }}
    <h2 class="title-text"> </h2> 
        <div class="card mb-4 ">
            <div class="card-header">
                <h2>Novedades</h2> 
            </div>
            <div class="card-body row ">
                @foreach ($libros->shuffle()->take(50) as $libro)
                    <div class="col-md-3 col-lg-2 mb-3 ">
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
