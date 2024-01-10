<?php

use App\Http\Controllers\ConfiguracionesTarjetaController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SocialesTarjetaController;
use App\Http\Controllers\UserTarjetaController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ComercioController;
use App\Http\Controllers\MaquinaController;
use App\Http\Controllers\BeerController;
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
    Route::get('auth/login', 'login');
    Route::post('auth/password/recuperar-password', 'solicitarOlvidoClave');
    Route::post('auth/password/cambiar-password', 'cambiarClave')->name('password.reset');

});

//USUARIO
Route::controller(UsuarioController::class)->group(function () {
    Route::post('usuario/crear', 'crearUsuario');
    Route::get('usuario/ver/{usuario}', 'verUsuario');
    Route::get('usuario/listar', 'listarUsuarios');
    Route::put('usuario/actualizar/{id}', 'actualizarUsuario');
    Route::delete('usuario/eliminar/{id}', 'eliminarUsuario');
});

//COMERCIO
Route::controller(ComercioController::class)->group(function () {
    Route::post('comercio/crear', 'crearComercio');
    Route::get('comercio/ver/{id}', 'verComercio');
    Route::get('comercio/listar', 'listarAllComercios');
    Route::put('comercio/actualizar/{id}', 'actualizarComercio');
    Route::delete('comercio/eliminar/{id}', 'eliminarComercio');
});

//MAQUINA
Route::controller(MaquinaController::class)->group(function () {
    Route::post('maquina/crear', 'crearMaquina');
    Route::get('maquina/ver/{id}', 'verMaquina');
    Route::get('maquina/listar', 'listarMaquina');
    Route::put('maquina/actualizar/{id}', 'actualizarMaquina');
    Route::delete('maquina/eliminar/{id}', 'eliminarMaquina');
});

//Maquina cerveza
Route::controller(BeerController::class)->group(function () {
    Route::post('sensor/beer/crear', 'crearBeerCode');
    Route::get('sensor/beer/escanear', 'escanearSensor');
    Route::put('usuario/beer/asignar', 'asignarTarjeta');
    Route::put('sensor/beer/vaciar', 'limpiarTarjeta');
    Route::get('maquinas/beer/listar', 'listadoMaquinas');
    Route::put('maquinas/beer/activar', 'activarMaquina');
    Route::get('maquina/beer/ver', 'verMaquina');
    Route::put('sensor/maquina/borrar', 'borrarSensorMaquina');
    Route::get('sensor/beer/listar', 'listadoTarjetas');
    Route::post('sensor/maquina/venta', 'crearConsumo');
    Route::get('sensor/consumos/listar', 'listarConsumos');

    Route::post('sensor/ventas/pagar', 'pagarVentas');
});
