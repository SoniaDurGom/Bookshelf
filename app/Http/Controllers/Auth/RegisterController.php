<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Lector;
use App\Models\Perfil;
use App\Models\Usuario;
use App\Providers\RouteServiceProvider;
// use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'nombre' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:perfiles'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            // 'perfil' => ['required', 'string', Rule::in(['autor', 'usuario'])],
            ]);
    }




    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $perfil = Perfil::create([
            'nombre' => $data['nombre'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    
        // if ($data['perfil'] == 'autor') {
        //     $perfil->autor()->create([
        //         'biografia' => '',
        //     ]);
        // } else {
        //     $perfil->usuario()->create();
        // }

        $usuario = new Lector();
        $usuario->perfil()->associate($perfil);
        $usuario->save();
    
        return $perfil;
    }
    
}
