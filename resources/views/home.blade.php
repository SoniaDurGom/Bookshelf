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
    @extends('layouts.app')

    @section('content')

        <div class="container">
            <div class="row">
                <div class="col-xl-4 col-md-6">
                    {{-- Izquierda --}}
                    <h5>Leyendo</h5>
                    <h5>Pendientes</h5>
                    <h5>Libreria</h5>


                </div>

                <div class="col-xl-4 d-none d-xl-block">
                    {{-- Centro --}}
                    <img class="img_index" src="{{ asset('img/index_img.jpeg') }}" alt="Foto central" >
                </div>
                

                <div class="col-xl-4 col-md-6">
                    {{-- Derecha --}}
                    <h5>Recomendaciones</h5>
                    <h5>Retos</h5>


                </div>

            </div>
        </div>




        
    @endsection

    
</body>
</html>