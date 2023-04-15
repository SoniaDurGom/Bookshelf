<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Bookshelf</title>
    <link rel="shortcut icon" href="{{ asset('favicons/favicon.ico') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    @extends('layouts.layout2')

    @section('content')
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-sm-12 col-md-12">
                    {{-- <div class="card">
                        <div class="card-header">{{ __('Panel de administración de ') }}  {{ $perfil->perfil->name }} </div>
                            {{ __('¡Bienvenid@ admin!') }}
                        </div>
                    </div> --}}

                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                          <button class="nav-link active" id="nav-libro-tab" data-bs-toggle="tab" data-bs-target="#nav-libro" type="button" role="tab" aria-controls="nav-libro" aria-selected="true">Libros</button>
                          <button class="nav-link" id="nav-autores-tab" data-bs-toggle="tab" data-bs-target="#nav-autor" type="button" role="tab" aria-controls="nav-autor" aria-selected="false">Autores</button>
                          <button class="nav-link" id="nav-solicitudLibro-tab" data-bs-toggle="tab" data-bs-target="#nav-solicitudLibro" type="button" role="tab" aria-controls="nav-solicitudLibro" aria-selected="false">Solicitudes</button>
                        </div>
                    </nav>

                      <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-libro" role="tabpanel" aria-labelledby="nav-libro-tab"> 
                            <div class="table-responsive" style="width: 100%; height: 85%; overflow-y: scroll;">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>ISBN</th>
                                            <th>Título</th>
                                            <th>Autor/es</th>
                                            <th>Portada</th>
                                            <th>Fecha de publicación</th>
                                            <th>Editorial</th>
                                            <th>Numero de páginas</th>
                                            <th>Acciones</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($libros as $libro)      
                                                <tr>
                                                <form action="{{route('administradores.actualizarLibro', $libro->id)}}" method="post">
                                                    @csrf
                                                    @method('PUT')
                                                    <td>
                                                        <input type="text" name="isbn" value="{{$libro->isbn}}" required>
                                                    </td>
                                                    <td>
                                                        <input type="text" name="titulo" value="{{$libro->titulo}}" required>
                                                    </td>
                                                    <td>
                                                        <input type="text" name="autores" required value="@foreach($libro->autorSinCuenta()->pluck('nombre', 'apellidos') as $nombre => $apellidos){{ $nombre }} {{ $apellidos }}@if(!$loop->last),@endif @endforeach">

                                                    </td>
                                                    <td>
                                                        <input type="text" name="portada" value="{{$libro->portada}}" required>
                                                    </td>
                                                    <td>
                                                        <input type="date" name="fechaPublicacion" value="{{$libro->fecha_publicacion}}" required>
                                                    </td>
                                                    <td>
                                                        <select name="editorial">
                                                            @foreach($editoriales as $editorial)
                                                                <option value="{{ $editorial->id }}" {{ $editorial->id === $libro->editorial->id ? 'selected' : '' }}>
                                                                    {{ $editorial->nombre }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </td>

                                                    <td>
                                                        <input type="number" class="form-control" id="numeroPaginas" name="numeroPaginas" required min="1" value="{{$libro->numero_paginas}}">
                                                    </td>
                                                    
                                                    <td>
                                                        <button type="submit" class="btn btn-primary btn-admin-añadir">Actualizar</button>
                                                        <button type="button" class="btn btn-danger btn-admin-eliminar" data-bs-toggle="modal" data-bs-target="#eliminarLibroModal{{$libro->id}}">Eliminar</button>
                                                    </td>
                                                </form>
                                            </tr>

                                        
                                            <!-- Modal para confirmar la eliminación del libro -->
                                            <div class="modal fade" id="eliminarLibroModal{{$libro->id}}" tabindex="-1" aria-labelledby="eliminarLibroModalLabel{{$libro->id}}" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="eliminarLibroModalLabel{{$libro->id}}">Eliminar libro</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            ¿Está seguro que desea eliminar el libro {{$libro->titulo}}?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                            <form action="{{route('administradores.eliminarLibro', $libro->id)}}" method="post">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger">Eliminar</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                        @endforeach

                                    

                                    </tbody>
                                </table>
                                @if (session('success'))
                                    <div class="alert alert-success">
                                        {{ session('success') }}
                                    </div>
                                @endif

                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        @foreach ($errors->all() as $error)
                                            <div>{{ $error }}</div>
                                        @endforeach
                                    </div>
                                @endif

                            
                            </div>



                            <br>

                            <div class="col-12" id="btnAñadirLibroAdmin">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#agregarLibroModal">Agregar libro</button>

                            </div>
                            <!-- Modal para agregar un nuevo libro -->
                            <div class="modal fade" id="agregarLibroModal" tabindex="-1" aria-labelledby="agregarLibroModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="agregarLibroModalLabel">Agregar libro</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{route('administradores.agregarLibro')}}" method="post">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="isbn" class="form-label">ISBN</label>
                                                    <input type="text" class="form-control" id="isbn" name="isbn" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="titulo" class="form-label">Título</label>
                                                    <input type="text" class="form-control" id="titulo" name="titulo" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="autores" class="form-label">Autor/es</label>
                                                    <input type="text" class="form-control" id="autores" name="autores" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="portada" class="form-label">Portada</label>
                                                    <input type="text" class="form-control" id="portada" name="portada" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="fechaPublicacion" class="form-label">Fecha de publicación</label>
                                                    <input type="date" class="form-control" id="fechaPublicacion" name="fechaPublicacion" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="editorial" class="form-label">Editorial</label>
                                                    <select class="form-select" id="editorial" name="editorial" required>
                                                        @foreach($editoriales as $editorial)
                                                            <option value="{{ $editorial->id }}">
                                                                {{ $editorial->nombre }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="numeroPaginas" class="form-label">Numero de páginas</label>
                                                    <input type="number" class="form-control" id="numeroPaginas" name="numeroPaginas" required min="1">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                <button type="submit" class="btn btn-primary">Agregar</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>




                        </div>
                        
                        <div class="tab-pane fade" id="nav-autor" role="tabpanel" aria-labelledby="nav-autores-tab">Aqui se van a mostrar las solicitudes de registro de los autores</div>
                        <div class="tab-pane fade" id="nav-solicitudLibro" role="tabpanel" aria-labelledby="nav-solicitudLibro-tab">Aqui se van a mostrar las solicitudes de libros de los usuarios</div>
                      </div>

                    
                </div>
            </div>
        </div>

       
    @endsection

    
</body>
</html>


