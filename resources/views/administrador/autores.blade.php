<div class="table-responsive" style="width: 100%; max-height: 600px; overflow-y: scroll;">
    <table class="table table-striped">
        <thead>
            <tr>
                <th></th>
                <th>Nombre</th>
                <th>Apellidos</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
          
            @foreach ($autoresSinCuenta as $autor)      
                        <tr>
                            <td>
                                <input type="checkbox" name="actualizarAutorSinCuenta[]" value="{{$autor->id}}" onchange="actualizarAutSeleccionados()">
                            </td>
                            
                            <form action="{{route('administradores.actualizarAutorSinCuenta', ['accion' => 'autor', 'id' => $autor->id])}}" method="post" id="form-autor-{{$autor->id}}">
                                @csrf
                                {{-- @method('PUT') --}}
                            
                                <td>
                                    <input type="text" name="nombre" value="{{$autor->nombre}}" required onblur="actualizarAutSeleccionados()">
                                </td>
                                <td>
                                    <input type="text" name="apellidos" value="{{$autor->apellidos}}" required onblur="actualizarAutSeleccionados()" >
                                </td>
                                
                                <td>
                                    <input type="hidden" name="autor_data" value={{$autor->id}} form="form-autor-{{$autor->id}}">
                                    <button type="submit" class="btn btn-primary btn-admin-añadir" form="form-autor-{{$autor->id}}">Actualizar</button>
                                    <button type="button" class="btn btn-danger btn-admin-eliminar" data-bs-toggle="modal" data-bs-target="#eliminarAutorSinCuentaModal{{$autor->id}}">Eliminar</button>
                                </td>
                        </form>
                    </tr>

                
                    <!-- Modal para confirmar la eliminación del autor -->
                    <div class="modal fade" id="eliminarAutorSinCuentaModal{{$autor->id}}" tabindex="-1" aria-labelledby="eliminarAutorSinCuentaModalLabel{{$autor->id}}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="eliminarAutorModalLabel{{$autor->id}}">Eliminar autor</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    ¿Está seguro que desea eliminar al autor no registrasdo:  {{$autor->nombre}}?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    <form action="{{route('administradores.eliminarAutorSinCuenta',['accion' => 'autor', 'id' => $autor->id])}}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="idAutorSinCuenta" id="idAutorSinCuenta" value="{{$autor->id}}">
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
        <form action="{{route('administradores.actualizarAutorSinCuenta',['accion' => 'bloque', 'id' => '0'])}}" method="post" id="form-sinCuenta-bloque" class="mx-1">
            @csrf
            <input type="hidden" name="autoresSinCuenta_seleccionados[]" id="autoresSinCuenta_seleccionados" value="">
          
            <button type="submit" id="actualizar-todosAutoresSinCuenta" class="btn btn-primary">Actualizar</button>
        </form>
        

        <form action="{{route('administradores.eliminarAutorSinCuenta',  ['accion' => 'bloque', 'id' => $autor->id])}}" method="post" id="form-borrarAutoresSinCuenta">
            @csrf
            @method('DELETE')
            <input type="hidden" name="autoresSinCuenta_seleccionados[]" id="autoresSinCuenta_seleccionadosBorrar" value="">
            <button type="submit" class="btn btn-danger">Eliminar</button>
        </form>
    </div>

  
        


  
    <hr class="d-sm-none b-block">

    <div class="col-12 col-sm-6 d-flex justify-content-center justify-content-sm-end m-2" id="btnAñadirLibroAdmin">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#agregarAutorSinCuentaModal">Agregar autor</button>
    </div>
    


</div>


@if (session('success-autores'))
    <div class="alert alert-success">
        {{ session('success-autores') }}
    </div>
@endif

@if (session('error-autores'))
    <div class="alert alert-danger">
        {{ session('error-autores') }}
    </div>
@endif

@if ($errors->any())
  <div class="alert alert-danger">
    @foreach ($errors->all() as $error)
      @if (strpos($error, 'nombre') !== false || strpos($error, 'apellidos') !== false)
        <div>{{ $error }}</div>
      @endif
    @endforeach
  </div>
@endif





<script>
    function actualizarAutSeleccionados() {
        var seleccionados = document.querySelectorAll('input[name="actualizarAutorSinCuenta[]"]:checked');
        var autoresSinCuentaSeleccionados = [];
        seleccionados.forEach(function(seleccionado) {
            autoresSinCuentaSeleccionados.push(seleccionado.value);
        });
        document.getElementById('autoresSinCuenta_seleccionados').value = autoresSinCuentaSeleccionados.join(',');
        document.getElementById('autoresSinCuenta_seleccionadosBorrar').value = autoresSinCuentaSeleccionados.join(',');

        // obtener los valores de los campos de cada autor seleccionado
        var datosAutoresSinCuenta = [];
        seleccionados.forEach(function(seleccionado) {
            var fila = seleccionado.closest('tr');
            var idAutorSinCuenta = seleccionado.value;
            var nombre = fila.querySelector('input[name="nombre"]').value;
            var apellidos = fila.querySelector('input[name="apellidos"]').value;
           
            

            datosAutoresSinCuenta.push({
                idAutorSinCuenta: idAutorSinCuenta,
                nombre: nombre,
                apellidos:apellidos,
               
            });
        });

        // agregar los datos de los autoresSinCuenta seleccionados al formulario
        var inputDatosAutoresSinCuenta = document.createElement('input');
        inputDatosAutoresSinCuenta.type = 'hidden';
        inputDatosAutoresSinCuenta.name = 'datos_autoresSinCuenta';
        inputDatosAutoresSinCuenta.value = JSON.stringify(datosAutoresSinCuenta);
        document.getElementById('form-sinCuenta-bloque').appendChild(inputDatosAutoresSinCuenta);
        console.log( seleccionados);
    }
</script>



<!-- Modal para agregar un nuevo autor -->
<div class="modal fade" id="agregarAutorSinCuentaModal" tabindex="-1" aria-labelledby="agregarAutorModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="agregarAutorSinCuentaModalLabel">Agregar autor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('administradores.agregarAutorSinCuenta')}}" method="post">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="apellidos" class="form-label">Apellidos</label>
                        <input type="text" class="form-control" id="apellidos" name="apellidos" required>
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
