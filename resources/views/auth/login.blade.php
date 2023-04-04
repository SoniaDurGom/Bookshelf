@extends('layouts.layout2')

@section('content')



<div class="container-fluid " style=" position: relative;  ">
    <img class="d-none d-md-block" src="{{ asset('/imgInicio.PNG') }}" alt="imagen" style="width:100%; height:300px; position: absolute; z-index: -1; left: 0;">
    <div class="row justify-content-end" style="z-index: 1;">
            <div class="col-xl-5 col-md-6 col-md-7 order-md-2 mt-5">
                <div class="card">
                    <div class="card-header">{{ __('Iniciar sesion') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('index.login') }}">
                            @csrf

                            <div class="row mb-3">
                                <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Correo electrónico') }}</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    

                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Contraseña') }}</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6 offset-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                        <label class="form-check-label" for="remember">
                                            {{ __('Recuerdame') }}
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Inicio') }}
                                    </button>

                                    @if (Route::has('password.request'))
                                        <a class="btn btn-link" href="{{ route('password.request') }}">
                                            {{ __('¿Has olvidado la contraseña?') }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <wbr>
            
        <div class="row" style="z-index: 1;">
        
            <div class="col-md-3 m-2" >
                <div>
                    <h5>¿Quieres un registro de tus lecturas?</h5>
                    <p>Encuentra, ordena y valora tu libreria personal más fácilmente que nunca.</p>
                </div>
            </div>

            <div class="col-md-3 m-2">

                <div>
                    <h5>¿Eres escritor?</h5>
                    <p>Si buscas un sitio en el que dar a conocer tu obra y una plataforma con la que poder interactuar con tus lectores ya la has encontrado.
                        <a href="{{ route('autores.login') }}">
                            {{ __('¿Te unes?') }}
                        </a>
                    </p>
                </div>
            </div>
        
        
        
        
        </div>


    </div>


</div>

           




@endsection
