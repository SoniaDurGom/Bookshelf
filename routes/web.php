<?php

use App\Http\Controllers\AdministradorController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\AutorController;
use App\Http\Controllers\AdminLoginController;
use App\Http\Controllers\LectorController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('index');
// });




Route::group(['middleware' => 'auth:lector'], function () {
    // rutas protegidas para lectores
});

Route::group(['middleware' => 'auth:autor'], function () {
    // rutas protegidas para autores
});

Route::group(['middleware' => 'auth:admin'], function () {
    // rutas protegidas para administradores
});

// rutas públicas
Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');




// Rutas para administradores
Route::get('/administradores/login', [AdministradorController::class, 'mostrarFormularioLogin'])->name('admin.login');
Route::post('/administradores/login', [AdministradorController::class, 'login'])->name('administradores.login');
Route::post('/administradores/logout', [AdministradorController::class, 'logout'])->name('administradores.logout');
Route::middleware('auth:administradores')->group(function () {
    Route::get('/administradores/panel-control', [AdministradorController::class, 'panelControl'])->name('administradores.panelControl');
    // Route::get('/panel-control', [AdministradorController::class, 'panelControl'])

});

// Rutas para autores
Route::get('/autores/registro', [AutorController::class, 'registro'])->name('autores.registro.formulario');
Route::post('/autores/registro', [AutorController::class, 'guardar'])->name('autores.guardar'); //Registro
Route::middleware('auth:autores')->group(function () {
    Route::get('/autores/panel-control', [AutorController::class, 'panelControl'])->name('autores.panelControl');
});

// Rutas para lectores
Route::get('/', [LectorController::class, 'formulario'])->name('lectores.login'); //Muestra Login principal de la pagina
Route::post('/', [LectorController::class, 'login'])->name('lectores.login'); //Login de lector por Post, se realiza el acceso con las credenciales del lector
// Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [LectorController::class, 'guardar'])->name('lectores.guardar');
Route::middleware('auth:lector')->group(function () {
    Route::get('/lectores/panel-control', [LectorController::class, 'panelControl'])->name('lectores.panelControl');
});




