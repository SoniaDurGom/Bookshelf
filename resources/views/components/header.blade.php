
<header>
    <nav class="navbar navbar-expand-lg bg-light">
        <div class="container-fluid">
          <a class="navbar-brand"  href="#"> <img id="logo" alt="logo" src="{{ asset('logo/bslogo.png') }}"> </a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse order-lg-0" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="#">Inicio</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="libro">Mis librerias</a>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  Buscar
                </a>
                <ul class="dropdown-menu">
                  <li><a class="dropdown-item" href="#">Action</a></li>
                  <li><a class="dropdown-item" href="#">Another action</a></li>
                  <li><hr class="dropdown-divider"></li>
                  <li><a class="dropdown-item" href="#">Something else here</a></li>
                </ul>
              </li><li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  Comunidad
                </a>
                <ul class="dropdown-menu">
                  <li><a class="dropdown-item" href="#">Action</a></li>
                  <li><a class="dropdown-item" href="#">Another action</a></li>
                  <li><hr class="dropdown-divider"></li>
                  <li><a class="dropdown-item" href="#">Something else here</a></li>
                </ul>
              </li>
            </ul>
            {{-- <div class="d-flex flex-row order-lg-1">
              <form class="d-flex" role="search">
                <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success " type="submit">Buscar</button>
              </form>
            </div> --}}
            {{-- <button id="log" routerLink="login"> <img id="user" alt="user" src="{{ asset('icons/usuario.png') }}"> </button> --}}

            <form class="d-flex" role="search">
              <input class="form-control me-2" type="search" placeholder="Buscar..." aria-label="Search">
              <button class="btn btn-outline-success " type="submit">Buscar</button>
            </form>
              <!-- Authentication Links -->
              @guest
                  @if (Route::has('login'))    
                      <li class="nav-item">
                          <a class="nav-link" href="{{ route('login') }}">{{ __('Inicio') }}</a>
                      </li>
                  @endif

                  @if (Route::has('register'))
                      <li class="nav-item ms-2">
                          <a class="nav-link" href="{{ route('register') }}">{{ __('Registrase') }}</a>
                      </li>
                  @endif
              @else
              <ul class="navbar-nav d-flex flex-row">
                  <li class="nav-item dropdown">
                      <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        <img id="user" alt="user" src="{{ asset('icons/usuario.png') }}">  
                        {{ $perfil->perfil->name }}
                          
                      </a>

                      <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">

                        <a class="dropdown-item" href="">
                          {{-- {{ route('cuenta') }} --}}
                              {{ __('Cuenta') }}
                          </a>

                          <a class="dropdown-item" href="{{ route('logout') }}"
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




     
