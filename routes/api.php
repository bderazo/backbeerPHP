<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\UsuarioController;
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

// //LOGIN Y REGISTRO
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
