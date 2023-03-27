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
    @extends('Layout.main') 
    @section('content')

    <h1>Contenido</h1>
    <div id="login">
        <div class="container">
            <div id="login-row" class="row justify-content-center align-items-center">
                <div id="login-column" class="col-md-6">
                    <div id="login-box" class="col-md-12">
                        <form id="login-form" class="form" action="" method="post">
                            <h3 class="text-center">Iniciar sesión</h3>
                            <div class="form-group">
                                <label for="username">Nombre de usuario:</label><br>
                                <input type="text" name="username" id="username" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="password" >Contraseña:</label><br>
                                <input type="text" name="password" id="password" class="form-control">
                            </div>
                         
                                <br><br>
                                
                                <input type="submit" name="submit" class="btn btn-info btn-md" value="Ok">
                                
                           
                            <div id="register-link" class="text-right">
                                <a href="#" >Crear cuenta</a> 
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <wbr>
   

    @endSection

    
</body>
</html>