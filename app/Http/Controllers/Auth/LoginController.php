<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use App\Models\Lector;
use App\Models\Perfil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }


    public function username()
    {
        return 'email';
    }

    //! PONER AQUI EL LOGIN DE LECTOR Y AUTOR. AMBOS SE VAN A PODER LOGEAR DESDE EL MISMO FORM
    //! pERO CADA UNO ACCEDE A UN PANEL DISTINTO

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $perfil = Perfil::where('email', $credentials['email'])->first();

        if (! $perfil || ! Hash::check($credentials['password'], $perfil->password)) {
            return back()->withErrors([
                'email' => 'Las credenciales proporcionadas no son válidas.',
            ]);
        }

        if($perfil->lector){
            $lector = $perfil->lector;

            if (! $lector) {
                return back()->withErrors([
                    'email' => 'El perfil no está asociado con ninguna cuenta.',
                ]);
            }

            Auth::guard('lector')->login($lector);

            return redirect()->route('lectores.panelControl');
        }

        if($perfil->autor){
            $autor = $perfil->autor;

            if (! $autor) { //No se encontró autor
                return back()->withErrors([
                    'email' => 'El perfil no está asociado con ninguna cuenta.',
                ]);
            }


            if($autor->aprobado==1){ //Si el autor ha sido aprobado puede accederl
                Auth::guard('autores')->login($autor);

                return redirect()->route('autores.panelControl');
            }else{
                return back()->withErrors([
                    'email' => 'Su cuenta de autor aún no ha sido aprobada.',
                ]);
            }
           
        }
        
    }
     


    // public function loginAutor(Request $request)
    // {
    //     $credentials = $request->validate([
    //         'email' => ['required', 'email'],
    //         'password' => ['required'],
    //     ]);

    //     $perfil = Perfil::where('email', $credentials['email'])->first();

    //     if (! $perfil || ! Hash::check($credentials['password'], $perfil->password)) {
    //         return back()->withErrors([
    //             'email' => 'Las credenciales proporcionadas no son válidas.',
    //         ]);
    //     }

    //     $autor = $perfil->autor;

    //     if (! $autor) {
    //         return back()->withErrors([
    //             'email' => 'El perfil no está asociado con ninguna cuenta.',
    //         ]);
    //     }

    //     Auth::guard('autor')->login($autor);

    //     return redirect()->route('autores.panelControl');
    // }
    

}


