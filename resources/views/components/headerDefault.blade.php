
<header>
    <nav class="navbar navbar-expand-lg bg-light">
        <div class="container-fluid">
          <a class="navbar-brand"  href="#"> <img id="logo" alt="logo" src="{{ asset('logo/bslogo.png') }}"> </a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
              <!-- Left Side Of Navbar -->
              {{-- <ul class="navbar-nav me-auto">
    
              </ul> --}}
    
              <!-- Right Side Of Navbar -->
              <ul class="navbar-nav ms-auto d-flex flex-row">
                  <!-- Authentication Links -->
                  @guest
                  {{-- @dd(Auth::guard('autores')->check()) --}}
                    @if (Auth::guard('autores')->check()||Auth::guard('administradores')->check())
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                <img id="user" alt="user" src="{{ asset('icons/usuario.png') }}">  
                                @if (Auth::guard('administradores')->check())
                                    {{ Auth::guard('administradores')->user()->perfil->name }}  
                                @else
                                    {{ Auth::guard('autores')->user()->perfil->name }}
                                @endif
                                
                            </a>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                @if (Auth::guard('administradores')->check())
                                    <a class="dropdown-item" href="{{ route('administradores.panelControl') }}">
                                        {{ __('Cuenta') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        {{ __('Salir') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                </form>
                                @else
                                    <a class="dropdown-item" href="{{ route('autores.panelControl') }}">
                                        {{ __('Cuenta') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        {{ __('Salir') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                @endif
                                
                            </div>
                        </li>
                    @else
                    {{-- Código para el usuario invitado --}}
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

                @endif

                     

                  @else
                  
                      <li class="nav-item dropdown">
                          <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            <img id="user" alt="user" src="{{ asset('icons/usuario.png') }}">  
                            @if ($perfil!=null)
                                {{ $perfil->perfil->name }}
                            @endif

                            
                              
                          </a>
    
                          <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">

                            {{-- <a class="dropdown-item" href="{{  Auth::guard('autores')->user() ? route('autores.panelControl') : route('administradores.panelControl') }}">
                              
                                  {{ __('Cuenta') }}
                              </a> --}}

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
                  @endguest
              </ul>
          </div>
    
            
    
          </div>
        </div>
      </nav>
</header>




     