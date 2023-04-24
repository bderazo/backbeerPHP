<?php

use App\Http\Controllers\ConfiguracionesTarjetaController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SocialesTarjetaController;
use App\Http\Controllers\UserTarjetaController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ComercioController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// //LOGIN
Route::controller(LoginController::class)->group(function () {
    Route::post('auth/login', 'login');
    // Route::post('auth/password/recuperar-password', 'solicitarOlvidoClave');
    // Route::post('auth/password/cambiar-password', 'cambiarClave')->name('password.reset');

});

//USUARIO
Route::controller(UsuarioController::class)->group(function () {
    Route::post('usuario/crear', 'crearUsuario');
    Route::post('usuario/ver/{usuario}', 'verUsuario');
    Route::post('usuario/listar', 'listarUsuarios');
    // Route::put('usuario/actualizar/{id}', 'actualizarUsuario');
    // Route::delete('usuario/eliminar/{id}', 'eliminarUsuario');
    // Route::put('usuario/password/cambiar', 'cambiarPassword');
    // Route::post('usuario/entidad/crear', 'crearUsuarioEntidad');
    // Route::put('usuario/entidad/actualizar/{id}', 'actualizarUsuarioEntidad');
    // Route::put('usuario/perfil/actualizar', 'actualizarPerfil');
    // Route::get('usuario/buscar', 'buscarUsuarioCedula');
    // Route::get('usuario/entidad/buscar', 'buscarUsuarioCedulaEntidad');
});

//COMERCIO
Route::controller(ComercioController::class)->group(function () {
    Route::post('comercio/crear', 'crearComercio');
    Route::post('comercio/ver/{id}', 'verComercio');
    Route::post('comercio/actualizar/{id}', 'actualizarComercio');
    Route::delete('comercio/eliminar/{id}', 'eliminarComercio');
    Route::post('comercio/listar', 'listarAllComercios');
    Route::post('tipo/comercio/listar', 'listarTipoComercios');
});


//TARJETA DE USUARIO
Route::controller(UserTarjetaController::class)->group(function () {
    Route::post('usuario/tarjeta/crear', 'crearUserTarjeta');
    Route::post('usuario/tarjeta/actualizar/{id}', 'actualizarUserTarjeta');
    Route::post('usuario/tarjeta/ver/{id}', 'verTarjetaUser');
});

//SOCIALES DE TARJETA DE USUARIO
Route::controller(SocialesTarjetaController::class)->group(function () {
    Route::post('tarjeta/sociales/crear', 'crearSocialesTarjeta');
    Route::post('tarjeta/sociales/actualizar/{id}', 'actualizarSocialesTarjeta');
});

//CONFIGURACIONES DE TARJETA DE USUARIO
Route::controller(ConfiguracionesTarjetaController::class)->group(function () {
    Route::post('tarjeta/configuraciones/crear', 'crearConfiguracionesTarjeta');
    Route::post('tarjeta/configuraciones/actualizar/{id}', 'actualizarConfiguracionesTarjeta');
});



