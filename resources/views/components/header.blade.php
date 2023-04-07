
<header>
  
    <nav class="navbar navbar-expand-lg bg-light">
        <div class="container-fluid">
          <a class="navbar-brand"  href="{{ route('index') }}"> <img id="logo" alt="logo" src="{{ asset('logo/bslogo.png') }}"> </a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse order-lg-0" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="{{ route('index') }}">Inicio</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="{{route('librerias.mostrar')}}">Mis librerias</a>
              </li>
              
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  <span class="text-truncate" style="max-width: 100px;" title="Buscar">Buscar</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-custom">
                  <li>
                    <div class="row">
                      <div class="col-6">
                        <a class="dropdown-item dropdown-item-white" href="{{route('libros.recomendaciones')}}">Recomendaciones</a>
                        <a class="dropdown-item dropdown-item-white" href="{{route('libros.novedades')}}">Novedades</a>
                        <a class="dropdown-item dropdown-item-white" href="{{route('libros.index')}}">Explorar</a>
                      </div>
                      <div class="col-6">
                        <h6 class="dropdown-header">Géneros favoritos</h6>
                        {{-- !Sacar los generos favoritos del usuario MAX 3 --}}
                        {{-- <a class="dropdown-item dropdown-item-white" href="{{route('genero.index',$perfil)}}">{{$perfil->genero}}</a> --}}
                       
                          @foreach(Auth::guard('lector')->user()->generosFavoritos as $genero)
                              <a class="dropdown-item dropdown-item-white" href="{{route('genero.index',$genero->nombre)}}">{{$genero->nombre}}</a>
                          @endforeach
                          <a class="dropdown-item dropdown-item-white" href="{{route('libros.index')}}">Todos</a>
                      </div>
                    </div>
                  </li>
                </ul>
              </li>
              
              
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  Comunidad
                </a>
                <ul class="dropdown-menu">
                  <li><a class="dropdown-item dropdown-item-white disabled" href="#">Grupos</a></li>
                  <li><a class="dropdown-item dropdown-item-white disabled" href="#">Amigos</a></li>
                  <li><hr class="dropdown-divider"></li>
                  <li><a class="dropdown-item dropdown-item-white" href="#">Autores</a></li>
                </ul>
              </li>
            </ul>

            <form class="d-flex" role="search">
              <input class="form-control me-2" type="search" placeholder="Buscar..." aria-label="Search">
              <button class="btn btn-outline-success " type="submit">Buscar</button>
            </form>
              <!-- Authentication Links -->
              @guest
              <ul class="navbar-nav d-flex flex-row">
                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                      <img id="user" alt="user" src="{{ asset('icons/usuario.png') }}">  
                      {{ $perfil->perfil->name }}
                        
                    </a>

                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">

                      <a class="dropdown-item dropdown-item-white" href="{{  Auth::guard('lector')->user() ? route('lectores.panelControl') : route('index.login') }}">
                        {{-- {{ route('cuenta') }} --}}
                            {{ __('Cuenta') }}
                        </a>

                        <a class="dropdown-item dropdown-item-white" href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                                         document.getElementById('logout-form').submit();">
                            {{ __('Salir') }}
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </li>
            </ul>
              @else
              <ul class="navbar-nav d-flex flex-row">
                  <li class="nav-item dropdown">
                      <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        <img id="user" alt="user" src="{{ asset('icons/usuario.png') }}">  
                        {{ $perfil->perfil->name }}
                          
                      </a>

                      <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">

                        <a class="dropdown-item dropdown-item-white" href="{{  Auth::guard('lector')->user() ? route('lectores.panelControl') : route('index.login') }}">
                          {{-- {{ route('cuenta') }} --}}
                              {{ __('Cuenta') }}
                          </a>

                          <a class="dropdown-item dropdown-item-white" href="{{ route('logout') }}"
                             onclick="event.preventDefault();
                                           document.getElementById('logout-form').submit();">
                              {{ __('Salir') }}
                          </a>

                          <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                              @csrf
                          </form>
                      </div>
                  </li>
              </ul>
              @endguest

            
    
          </div>
        </div>
      </nav>
</header>




     
