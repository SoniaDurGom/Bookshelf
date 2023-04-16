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

                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                          <button class="nav-link active" id="nav-libro-tab" data-bs-toggle="tab" data-bs-target="#nav-libro" type="button" role="tab" aria-controls="nav-libro" aria-selected="true">Libros</button>
                          <button class="nav-link" id="nav-autores-tab" data-bs-toggle="tab" data-bs-target="#nav-autor" type="button" role="tab" aria-controls="nav-autor" aria-selected="false">Autores</button>
                          <button class="nav-link" id="nav-editoriales-tab" data-bs-toggle="tab" data-bs-target="#nav-editoriales" type="button" role="tab" aria-controls="nav-editoriales" aria-selected="false">Editoriales</button>
                          <button class="nav-link" id="nav-solicituAutores-tab" data-bs-toggle="tab" data-bs-target="#nav-SolicitudAutor" type="button" role="tab" aria-controls="nav-autor" aria-selected="false" disabled>Solicitud autores</button>
                          <button class="nav-link" id="nav-solicitudLibro-tab" data-bs-toggle="tab" data-bs-target="#nav-solicitudLibro" type="button" role="tab" aria-controls="nav-solicitudLibro" aria-selected="false" disabled>Solicitudes libros</button>
                        </div>
                    </nav>

                      <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-libro" role="tabpanel" aria-labelledby="nav-libro-tab"> 
                            <div class="table-responsive" style="width: 100%; max-height: 600px; overflow-y: scroll;">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>ISBN</th>
                                            <th>Título</th>
                                            <th>Autor/es</th>
                                            <th>Portada</th>
                                            <th>Fecha de publicación</th>
                                            <th>Editorial</th>
                                            <th>Páginas</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                      
                                        @foreach ($libros as $libro)      
                                                    <tr>
                                                        <td>
                                                            <input type="checkbox" name="actualizar[]" value="{{$libro->id}}" onchange="actualizarSeleccionados()">
                                                        </td>
                                                        
                                                        <form action="{{route('administradores.actualizar', ['accion' => 'libro', 'id' => $libro->id])}}" method="post" id="form-libro-{{$libro->id}}">
                                                            @csrf
                                                            {{-- @method('PUT') --}}
                                                        
                                                            <td>
                                                                <input type="text" name="isbn" value="{{$libro->isbn}}" required onblur="actualizarSeleccionados()">
                                                            </td>
                                                            <td>
                                                                <input type="text" name="titulo" value="{{$libro->titulo}}" required onblur="actualizarSeleccionados()" >
                                                            </td>
                                                            <td>
                                                                <input type="text" name="autores" required onblur="actualizarSeleccionados()" value="@foreach($libro->autorSinCuenta()->pluck('nombre', 'apellidos') as $nombre => $apellidos){{ $nombre }} {{ $apellidos }}@if(!$loop->last),@endif @endforeach">
                                                            </td>
                                                            <td>
                                                                <input type="text" name="portada" value="{{$libro->portada}}" required onblur="actualizarSeleccionados()">
                                                            </td>
                                                            <td>
                                                                <input type="date" name="fechaPublicacion" value="{{$libro->fecha_publicacion}}" required onblur="actualizarSeleccionados()">
                                                            </td>
                                                            <td>
                                                                <select name="editorial" onblur="actualizarSeleccionados()">
                                                                    @foreach($editoriales as $editorial)
                                                                        <option value="{{ $editorial->id }}" {{ $editorial->id === $libro->editorial->id ? 'selected' : '' }}>
                                                                            {{ $editorial->nombre }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </td>

                                                            <td>
                                                                <input type="number" class="form-control" id="numeroPaginas" name="numeroPaginas" required min="1" value="{{$libro->numero_paginas}}" onblur="actualizarSeleccionados()">
                                                            </td>
                                                     
                                                            <td>
                                                                <input type="hidden" name="libro_data" value="" form="form-libro-{{$libro->id}}">
                                                                <button type="submit" class="btn btn-primary btn-admin-añadir" form="form-libro-{{$libro->id}}">Actualizar</button>
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
                                                                <form action="{{route('administradores.eliminar',['accion' => 'libro', 'id' => $libro->id])}}" method="post">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <input type="hidden" name="idLibro" id="idLibro" value="{{$libro->id}}">
                                                                    <button type="submit" class="btn btn-danger">Eliminar</button>

                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>


                                            @endforeach

                                        </tbody>
                                    </table>
                            
                            </div>

                            <br>


                            <div class="row">
                                <h5>Acciones en bloque</h5>
                                <div class="col-5 d-flex m-2">
                                    <form action="{{route('administradores.actualizar', ['accion' => 'bloque'])}}" method="post" id="form-bloque" class="mx-1">
                                        @csrf
                                        <input type="hidden" name="libros_seleccionados[]" id="libros_seleccionados" value="">
                                        <button type="submit" id="actualizar-todos" class="btn btn-primary">Actualizar</button>
                                    </form>

                                    <form action="{{route('administradores.eliminar',  ['accion' => 'bloque'])}}" method="post" id="form-borrar">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="libros_seleccionados[]" id="libros_seleccionadosBorrar" value="">
                                        <button type="submit" class="btn btn-danger">Eliminar</button>
                                    </form>
                                </div>

                              
                                <hr class="d-sm-none b-block">

                                <div class="col-12 col-sm-6 d-flex justify-content-center justify-content-sm-end m-2" id="btnAñadirLibroAdmin">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#agregarLibroModal">Agregar libro</button>
                                </div>
                                
                           

                            </div>
                            

                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif

                            @if (session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    @foreach ($errors->all() as $error)
                                        <div>{{ $error }}</div>
                                    @endforeach
                                </div>
                            @endif




                            <script>
                                function actualizarSeleccionados() {
                                    var seleccionados = document.querySelectorAll('input[name="actualizar[]"]:checked');
                                    var librosSeleccionados = [];
                                    seleccionados.forEach(function(seleccionado) {
                                        librosSeleccionados.push(seleccionado.value);
                                    });
                                    document.getElementById('libros_seleccionados').value = librosSeleccionados.join(',');
                                    document.getElementById('libros_seleccionadosBorrar').value = librosSeleccionados.join(',');
                    
                                    // obtener los valores de los campos de cada libro seleccionado
                                    var datosLibros = [];
                                    seleccionados.forEach(function(seleccionado) {
                                        var fila = seleccionado.closest('tr');
                                        var idLibro = seleccionado.value;
                                        var isbn = fila.querySelector('input[name="isbn"]').value;
                                        var titulo = fila.querySelector('input[name="titulo"]').value;
                                        var autores = fila.querySelector('input[name="autores"]').value;
                    
                                        var portada = fila.querySelector('input[name="portada"]').value;
                                        var fechaPublicacion = fila.querySelector('input[name="fechaPublicacion"]').value;
                                        var editorial = fila.querySelector('select[name="editorial"]').value;
                                        var numeroPaginas = fila.querySelector('input[name="numeroPaginas"]').value;
                                        
                    
                                        datosLibros.push({
                                            id: idLibro,
                                            isbn: isbn,
                                            titulo: titulo,
                                            autores:autores,
                                            portada: portada,
                                            fechaPublicacion:fechaPublicacion,
                                            editorial:editorial,
                                            numeroPaginas: numeroPaginas
                                        });
                                    });
                    
                                    // agregar los datos de los libros seleccionados al formulario
                                    var inputDatosLibros = document.createElement('input');
                                    inputDatosLibros.type = 'hidden';
                                    inputDatosLibros.name = 'datos_libros';
                                    inputDatosLibros.value = JSON.stringify(datosLibros);
                                    document.getElementById('form-bloque').appendChild(inputDatosLibros);
                                }
                            </script>



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
                        
                        <div class="tab-pane fade" id="nav-autor" role="tabpanel" aria-labelledby="nav-autores-tab">Aqui se van a mostrar los autores sin cuenta</div>
                        <div class="tab-pane fade" id="nav-editoriales" role="tabpanel" aria-labelledby="nav-editorialeses-tab">Aqui se van a mostrar las editoriales</div>
                        <div class="tab-pane fade" id="nav-solicitudLibro" role="tabpanel" aria-labelledby="nav-solicitudLibro-tab">Aqui se van a mostrar las solicitudes de libros de los usuarios</div>
                        <div class="tab-pane fade" id="nav-soliciturdAutor" role="tabpanel" aria-labelledby="nav-soliciturdAutores-tab">Aqui se van a mostrar las solicitudes de registro de los autores</div>
                    </div>

                    
                </div>
            </div>
        </div>
        
       
    @endsection

    
</body>
</html>


