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
                            <button class="nav-link @if(session('tab') == 'libros') active @endif" id="nav-libro-tab" data-bs-toggle="tab" data-bs-target="#nav-libro" type="button" role="tab" aria-controls="nav-libro" aria-selected="true">Libros</button>
                            <button class="nav-link @if(session('tab') == 'autores') active @endif" id="nav-autores-tab" data-bs-toggle="tab" data-bs-target="#nav-autor" type="button" role="tab" aria-controls="nav-autor" aria-selected="false">Autores</button>
                            <button class="nav-link @if(session('tab') == 'editoriales') active @endif" id="nav-editoriales-tab" data-bs-toggle="tab" data-bs-target="#nav-editoriales" type="button" role="tab" aria-controls="nav-editoriales" aria-selected="false">Editoriales</button>
                            <button class="nav-link @if(session('tab') == 'solicitudAutor') active @endif" id="nav-solicituAutores-tab" data-bs-toggle="tab" data-bs-target="#nav-SolicitudAutor" type="button" role="tab" aria-controls="nav-soliciturdAutores" aria-selected="false" disabled>Solicitud autores</button>
                            <button class="nav-link @if(session('tab') == 'solicitudLibro') active @endif" id="nav-solicitudLibro-tab" data-bs-toggle="tab" data-bs-target="#nav-solicitudLibro" type="button" role="tab" aria-controls="nav-solicitudLibro" aria-selected="false" disabled>Solicitudes libros</button>
                        </div>
                        
                    </nav>

                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade @if(session('tab') == 'libros') show active @endif" id="nav-libro" role="tabpanel" aria-labelledby="nav-libro-tab"> 
                            @include('administrador.libros')
                        </div>
                    
                        <div class="tab-pane fade @if(session('tab') == 'autores') show active @endif" id="nav-autor" role="tabpanel" aria-labelledby="nav-autores-tab">
                            @include('administrador.autores')
                        </div>
                    
                        <div class="tab-pane fade @if(session('tab') == 'editoriales') show active @endif" id="nav-editoriales" role="tabpanel" aria-labelledby="nav-editorialeses-tab">
                            Aqui se van a mostrar las editoriales
                        </div>
                    
                        <div class="tab-pane fade @if(session('tab') == 'solicitudLibro') show active @endif" id="nav-solicitudLibro" role="tabpanel" aria-labelledby="nav-solicitudLibro-tab">
                            Aqui se van a mostrar las solicitudes de libros de los usuarios
                        </div>
                    
                        <div class="tab-pane fade @if(session('tab') == 'solicitudAutor') show active @endif" id="nav-soliciturdAutor" role="tabpanel" aria-labelledby="nav-soliciturdAutores-tab">
                            Aqui se van a mostrar las solicitudes de registro de los autores
                        </div>
                    </div>
                    

                    
                </div>
            </div>
        </div>
        
       
    @endsection

    
</body>
</html>


