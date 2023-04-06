<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Bookshelf</title>
    <link rel="shortcut icon" href="{{ asset('favicons/favicon.ico') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> --}}
    <script src="{{ asset('js/mostrarPwd.js') }}"></script>

</head>
<body>
    @extends('layouts.app')

    @section('content')
        <div class="container">
            <div class="row justify-content-center">
                <div class="col col-md-10">
                    <div class="card">
                        <div class="card-header">{{ __('Ajustes de cuenta ') }} </div>

                        <div class="card-body">
                            @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif

                            <div class= "row">
                                <div class= "col-10 col-lg-6 ">
                                   
                                    <form action="{{ route('lectores.cambiarAjustes') }}" method="POST">
                                        @csrf
                                        <label for="name">Nombre: </label>
                                        <input type="text" name="name" value="{{ $perfil->perfil->name }}" required > <br> <br>
                                        @if ($errors->has('name'))
                                          <div class="alert alert-danger">{{ $errors->first('name') }}</div>
                                        @endif
                                      
                                        <label for="email">Correo electrónico: </label>
                                        <input type="text" name="email" value="{{ $perfil->perfil->email }}" required > <br> <br>
                                        @if ($errors->has('email'))
                                          <div class="alert alert-danger">{{ $errors->first('email') }}</div>
                                        @endif
                                      
                                        <a id="cambiar-contrasena" class="btn btn-link">Cambiar contraseña</a>
                                      
                                        <div id="password-fields" style="display:none;">
                                          <label for="password">Nueva contraseña: </label>
                                          <input type="password" name="password" placeholder="*********" > <br> <br>

                                          <label for="password-confirm">Confirmar contraseña: </label>
                                          <input id="password-confirm" type="password"  name="password_confirmation" placeholder="*********"  autocomplete="new-password">
                                        </div>
                                      
                                        <br> <br>
                                      
                                        <button type="submit" class="btn btn-primary"> Actualizar </button>
                                      </form>
                                      
                                   
                                   
                                    
                                  

                                </div>

                                <br>

                                <div class= "col-5 col-xl-5">
                                    <wbr>
                                    <!-- foto -->
                                    <div class="avatar">
                                        @if ($perfil->foto)
                                            <img src="{{ asset($perfil->foto) }}" alt="Foto de perfil">
                                        @else
                                            <img src="{{ asset('img/default-profile.png') }}" alt="Foto de perfil">
                                        @endif

                                    </div>
                                
                                    <br>

                                    <form action="{{ route('lectores.subir-foto') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <input type="file" name="foto"> <br><br>
                                        <button class="btn btn-primary" type="submit">Subir</button>
                                    </form>
                                    <br>
                                    <form action="{{ route('lectores.borrarCuenta') }}" method="POST" onsubmit="return confirm('¿Estás seguro de que quieres eliminar tu cuenta?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-danger boton-rojo"> Borrar cuenta </button>
                                        <wbr>
                                    </form>













                                   
                                    

                                   
                                    
                                </div>

                            </div>


                               


                            





                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    
    
</body>
</html>