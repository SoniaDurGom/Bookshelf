@extends('layouts.app')

@section('content')


<div class="container mt-4">
    <div class="card">
        <div class="card-header">
          <h3 class="card-title">{{ $reto->anio }} Reto de lectura</h3>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <p><strong>Libros objetivo:</strong> {{ $reto->libros_objetivo }}</p>
            </div>
            <div class="col.sm-4 col-md-4  col-lg-3 text-md-right">
              <form action="{{ route('reto.marcarObjetivo', $reto->id) }}" method="POST">
                @csrf
                <div class="form-group mb-0">
                    <label for="libros_objetivo">Libros objetivo:</label>
                    <div class="input-group">
                      <input type="number" class="form-control" id="libros_objetivo" name="libros_objetivo" value="{{ $reto->libros_objetivo }}">
                      <div class="input-group-append">
                        <button type="submit" class="btn btn-primary mx-2">Actualizar</button>
                      </div>
                    </div>
                </div>
              </form>
            </div>
          </div>

          @if(session('success'))
            <div class="alert alert-success my-3" role="alert">
              {{ session('success') }}
            </div>
          @endif

          <hr>

          <div class="row">
            <div class="col-md-6">
              <p><strong>Libros leídos:</strong> {{ $reto->libros_leidos }}</p>
            </div>
            <div class="col-md-6 text-md-right">
              <p><strong>Reto completado:</strong> {{ $reto->completado ? 'Sí' : 'No' }}</p>
            </div>
          </div>

          <p><strong>Páginas de los libros leídas en total:</strong> {{ $totalPaginasLeidas }}</p>
        </div>
      </div>
</div>



@endsection
