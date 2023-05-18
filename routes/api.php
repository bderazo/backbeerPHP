<?php

use App\Http\Controllers\CategoriaProductoController;
use App\Http\Controllers\ConfiguracionesTarjetaController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProductoController;
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
});

//USUARIO
Route::controller(UsuarioController::class)->group(function () {
    Route::post('usuario/crear', 'crearUsuario');
    Route::post('usuario/ver/{usuario}', 'verUsuario');
    Route::post('usuario/listar', 'listarUsuarios');
});

//COMERCIO
Route::controller(ComercioController::class)->group(function () {
    Route::post('comercio/crear', 'crearComercio');
    Route::post('sucursal/crear', 'crearSucursal');
    Route::post('mesa/crear', 'crearMesaSucursal');
    Route::post('mesas/listar/{id}', 'listarMesasIdSucursal');
    Route::post('comercio/ver/{id}', 'verComercio');
    Route::post('comercio/actualizar/{id}', 'actualizarComercio');
    Route::delete('comercio/eliminar/{id}', 'eliminarComercio');
    Route::post('comercio/listar/todos', 'listarAllComercios');
    Route::post('sucursal/listar/{id}', 'listarSucursalesIdComercio');
    Route::post('comercio/listar', 'listarComercios'); //paginado de 10 en 10
    Route::post('tipo/comercio/listar', 'listarTipoComercios');
});

//CATEGORIAS PRODUCTO
Route::controller(CategoriaProductoController::class)->group(function () {
    Route::post('categoria/producto/crear', 'crearCategoria');
    Route::post('categoria/producto/ver/{id}', 'verCategoria');
    Route::post('categoria/producto/listar', 'listarAllCategorias');
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

//PRODUCTO
Route::controller(ProductoController::class)->group(function () {
    Route::post('producto/crear', 'crearProducto');
    Route::post('productos/listar/todos', 'listarAllProductos');
});