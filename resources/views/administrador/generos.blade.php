<div class="table-responsive" style="width: 100%; max-height: 600px; overflow-y: scroll;">
    <table class="table table-striped">
        <thead>
            <tr>
                <th></th>
                <th>Nombre</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
          
            @foreach ($generos as $genero)      
                        <tr>
                            <td>
                                <input type="checkbox" name="actualizarGenero[]" value="{{$genero->id}}" onchange="actualizarGenerosSeleccionados()">
                            </td>
                            
                            <form action="{{route('administradores.actualizarGenero', ['accion' => 'genero', 'id' => $genero->id])}}" method="post" id="form-genero-{{$genero->id}}">
                                @csrf
                                {{-- @method('PUT') --}}
                            
                                <td>
                                    <input type="text" name="nombre" value="{{$genero->nombre}}" required onblur="actualizarGenerosSeleccionados()">
                                </td>
                    
                                <td>
                                    <input type="hidden" name="genero_data" value={{$genero->id}} form="form-genero-{{$genero->id}}">
                                    <button type="submit" class="btn btn-primary btn-admin-añadir" form="form-genero-{{$genero->id}}">Actualizar</button>
                                    <button type="button" class="btn btn-danger btn-admin-eliminar no-margin"  data-bs-toggle="modal" data-bs-target="#eliminarGeneroModal{{$genero->id}}">Eliminar</button>
                                </td>
                        </form>
                    </tr>

                
                    <!-- Modal para confirmar la eliminación del genero -->
                    <div class="modal fade" id="eliminarGeneroModal{{$genero->id}}" tabindex="-1" aria-labelledby="eliminarGeneroModalLabel{{$genero->id}}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="eliminarGeneroModalLabel{{$genero->id}}">Eliminar genero</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    ¿Está seguro que desea eliminar el genero:  {{$genero->nombre}}?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    <form action="{{route('administradores.eliminarGenero',['accion' => 'genero', 'id' => $genero->id])}}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="idGenero" id="idGenero" value="{{$genero->id}}">
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
        <form action="{{route('administradores.actualizarGenero',['accion' => 'bloque', 'id' => '0'])}}" method="post" id="form-genero-bloque" class="mx-1">
            @csrf
            <input type="hidden" name="generos_seleccionados[]" id="generos_seleccionados" value="">
          
            <button type="submit" id="actualizar-todosGeneros" class="btn btn-primary">Actualizar</button>
        </form>
        

        <form action="{{route('administradores.eliminarGenero',  ['accion' => 'bloque', 'id' => $genero->id])}}" method="post" id="form-borrarGeneros">
            @csrf
            @method('DELETE')
            <input type="hidden" name="generos_seleccionados[]" id="generos_seleccionadosBorrar" value="">
            <button type="submit" class="btn btn-danger">Eliminar</button>
        </form>
    </div>

  
        


  
    <hr class="d-sm-none b-block">

    <div class="col-12 col-sm-6 d-flex justify-content-center justify-content-sm-end m-2" id="btnAñadirGeneroAdmin">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#agregarGeneroModal">Agregar genero</button>
    </div>
    


</div>


@if (session('success-generos'))
    <div class="alert alert-success">
        {{ session('success-generos') }}
    </div>
@endif

@if (session('error-generos'))
    <div class="alert alert-danger">
        {{ session('error-generos') }}
    </div>
@endif

@if ($errors->any())
  <div class="alert alert-danger">
    @foreach ($errors->all() as $error)
      @if (strpos($error, 'nombre') !== false)
        <div>{{ $error }}</div>
      @endif
    @endforeach
  </div>
@endif





<script>
    function actualizarGenerosSeleccionados() {
        var seleccionados = document.querySelectorAll('input[name="actualizarGenero[]"]:checked');
        var generosSeleccionados = [];
        seleccionados.forEach(function(seleccionado) {
            generosSeleccionados.push(seleccionado.value);
        });
        document.getElementById('generos_seleccionados').value = generosSeleccionados.join(',');
        document.getElementById('generos_seleccionadosBorrar').value = generosSeleccionados.join(',');

        // obtener los valores de los campos de cada genero seleccionado
        var datosGeneros = [];
        seleccionados.forEach(function(seleccionado) {
            var fila = seleccionado.closest('tr');
            var idGenero = seleccionado.value;
            var nombre = fila.querySelector('input[name="nombre"]').value;
           
            datosGeneros.push({
                idGenero: idGenero,
                nombre: nombre,
            });
        });

        // agregar los datos de los generos seleccionados al formulario
        var inputDatosGeneros = document.createElement('input');
        inputDatosGeneros.type = 'hidden';
        inputDatosGeneros.name = 'datos_generos';
        inputDatosGeneros.value = JSON.stringify(datosGeneros);
        document.getElementById('form-genero-bloque').appendChild(inputDatosGeneros);
        console.log( seleccionados);
    }
</script>



<!-- Modal para agregar un nuevo genero -->
<div class="modal fade" id="agregarGeneroModal" tabindex="-1" aria-labelledby="agregarGeneroModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="agregarGeneroModalLabel">Agregar genero</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('administradores.agregarGenero')}}" method="post">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
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
