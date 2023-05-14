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
          
            @foreach ($editoriales as $editorial)      
                        <tr>
                            <td>
                                <input type="checkbox" name="actualizarEditorial[]" value="{{$editorial->id}}" onchange="actualizarEditorialesSeleccionados()">
                            </td>
                            
                            <form action="{{route('administradores.actualizarEditorial', ['accion' => 'editorial', 'id' => $editorial->id])}}" method="post" id="form-editorial-{{$editorial->id}}">
                                @csrf
                                {{-- @method('PUT') --}}
                            
                                <td>
                                    <input type="text" name="nombre" value="{{$editorial->nombre}}" required onblur="actualizarEditorialesSeleccionados()">
                                </td>
                    
                                <td>
                                    <input type="hidden" name="editorial_data" value={{$editorial->id}} form="form-editorial-{{$editorial->id}}">
                                    <button type="submit" class="btn btn-primary btn-admin-añadir" form="form-editorial-{{$editorial->id}}">Actualizar</button>
                                    <button type="button" class="btn btn-danger btn-admin-eliminar no-margin"  data-bs-toggle="modal" data-bs-target="#eliminarEditorialModal{{$editorial->id}}">Eliminar</button>
                                </td>
                        </form>
                    </tr>

                
                    <!-- Modal para confirmar la eliminación del editorial -->
                    <div class="modal fade" id="eliminarEditorialModal{{$editorial->id}}" tabindex="-1" aria-labelledby="eliminarEditorialModalLabel{{$editorial->id}}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="eliminarEditorialModalLabel{{$editorial->id}}">Eliminar editorial</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    ¿Está seguro que desea eliminar el editorial:  {{$editorial->nombre}}?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    <form action="{{route('administradores.eliminarEditorial',['accion' => 'editorial', 'id' => $editorial->id])}}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="idEditorial" id="idEditorial" value="{{$editorial->id}}">
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
        <form action="{{route('administradores.actualizarEditorial',['accion' => 'bloque', 'id' => '0'])}}" method="post" id="form-editorial-bloque" class="mx-1">
            @csrf
            <input type="hidden" name="editoriales_seleccionados[]" id="editoriales_seleccionados" value="">
          
            <button type="submit" id="actualizar-todosEditoriales" class="btn btn-primary">Actualizar</button>
        </form>
        

        <form action="{{route('administradores.eliminarEditorial',  ['accion' => 'bloque', 'id' => $editorial->id])}}" method="post" id="form-borrarEditoriales">
            @csrf
            @method('DELETE')
            <input type="hidden" name="editoriales_seleccionados[]" id="editoriales_seleccionadosBorrar" value="">
            <button type="submit" class="btn btn-danger">Eliminar</button>
        </form>
    </div>

  
        


  
    <hr class="d-sm-none b-block">

    <div class="col-12 col-sm-6 d-flex justify-content-center justify-content-sm-end m-2" id="btnAñadirEditorialAdmin">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#agregarEditorialModal">Agregar editorial</button>
    </div>
    


</div>


@if (session('success-editoriales'))
    <div class="alert alert-success">
        {{ session('success-editoriales') }}
    </div>
@endif

@if (session('error-editoriales'))
    <div class="alert alert-danger">
        {{ session('error-editoriales') }}
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
    function actualizarEditorialesSeleccionados() {
        var seleccionados = document.querySelectorAll('input[name="actualizarEditorial[]"]:checked');
        var editorialesSeleccionados = [];
        seleccionados.forEach(function(seleccionado) {
            editorialesSeleccionados.push(seleccionado.value);
        });
        document.getElementById('editoriales_seleccionados').value = editorialesSeleccionados.join(',');
        document.getElementById('editoriales_seleccionadosBorrar').value = editorialesSeleccionados.join(',');

        // obtener los valores de los campos de cada editorial seleccionado
        var datosEditoriales = [];
        seleccionados.forEach(function(seleccionado) {
            var fila = seleccionado.closest('tr');
            var idEditorial = seleccionado.value;
            var nombre = fila.querySelector('input[name="nombre"]').value;
           
            datosEditoriales.push({
                idEditorial: idEditorial,
                nombre: nombre,
            });
        });

        // agregar los datos de los editoriales seleccionados al formulario
        var inputDatosEditoriales = document.createElement('input');
        inputDatosEditoriales.type = 'hidden';
        inputDatosEditoriales.name = 'datos_editoriales';
        inputDatosEditoriales.value = JSON.stringify(datosEditoriales);
        document.getElementById('form-editorial-bloque').appendChild(inputDatosEditoriales);
        console.log( seleccionados);
    }
</script>



<!-- Modal para agregar un nuevo editorial -->
<div class="modal fade" id="agregarEditorialModal" tabindex="-1" aria-labelledby="agregarEditorialModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="agregarEditorialModalLabel">Agregar editorial</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('administradores.agregarEditorial')}}" method="post">
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
