@extends('layouts.app')

@section('content')



<div class="container">
    <div class="card">
        <div class="card-header">
          <h3 class="card-title">{{ $reto->anio }} Reto de lectura</h3>
        </div>
        <div class="card-body">
          {{-- <img id="img-reto" src="{{ asset('img/retoSinFondo.jpeg') }}" alt="imagen reto literario año 2023"> --}}
          <p><strong>Libros objetivo:</strong> {{ $reto->libros_objetivo }}</p>
          <form action="{{ route('reto.marcarObjetivo', $reto->id) }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="libros_objetivo">Libros objetivo</label>
                <input type="text" class="form-control" id="libros_objetivo" name="libros_objetivo" value="{{ $reto->libros_objetivo }}">
            </div>

            <button type="submit" class="btn btn-primary">Actualizar</button>
        </form>

          <p><strong>Libros leídos:</strong> {{ $reto->libros_leidos }}</p>
          <p><strong>Reto completado:</strong> {{ $reto->completado ? 'Sí' : 'No' }}</p>
          <p><strong>Páginas de los libros leídas en total:</strong> {{ $totalPaginasLeidas }}</p>
        </div>
      </div>
      
</div>



@endsection
