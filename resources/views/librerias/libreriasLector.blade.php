@extends('layouts.app')

@section('content')

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Lector</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($librerias as $libreria)
        <tr>
            <td>{{ $libreria->id }}</td>
            <td>{{ $libreria->nombre }}</td>
            <td>{{ $libreria->lector->nombre }}</td>
            <td>
                <a href="{{ route('librerias.libros', $libreria->nombre) }}">Mostrar libros</a>
                @if($libreria->nombre != 'Leyendo' && $libreria->nombre != 'Leído' && $libreria->nombre != 'Quiero leer')
                    <form action="{{ route('librerias.borrarLibreria', $libreria->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-primary" type="submit">Borrar</button>
                    </form>
                @endif
            </td>
          
        </tr>
        @endforeach

        
    </tbody>
</table>

    <form action="{{ route('librerias.crearLibreria') }}" method="POST">
        @csrf
        <label for="nuevaLibreria">Libreria:</label>
        <input type="text" name="nuevaLibreria" id="">
        <button class="btn btn-primary" type="submit">Nueva libreria</button>
    </form>



@endsection
