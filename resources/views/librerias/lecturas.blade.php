
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







@endsection

