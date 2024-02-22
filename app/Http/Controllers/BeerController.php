<?php

namespace App\Http\Controllers;

use App\Models\ArrayExport;
use App\Models\Pulsera;
use App\Models\Consumo;
use App\Models\Usuario;
use App\Models\Maquina;
use App\Models\Venta;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;

define('MENSAJE_ERROR', 'Error al validar los datos de entrada.');
define('MENSAJE_NO_AUTORIZADO', 'No autorizado!.');
define('MENSAJE_ERROR2', 'Ocurrio un error!.');

class BeerController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth:api');
    }

    public function crearBeerCode(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), [
                'id_cliente' => 'nullable|exists:usuarios,id',
                'cupo_maximo' => 'nullable',
                'estado' => 'nullable',
                'tipo_sensor' => 'required',
                'codigo_sensor' => 'required|unique:pulsera,codigo_sensor',
                'usuario_registra' => 'exists:usuarios,id',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => 422,
                    'message' => MENSAJE_ERROR,
                    'data' => $validator->errors()
                ], 422);
            } else {
                $tarjeta = new Pulsera([
                    'id_cliente' => $request->id_cliente,
                    'cupo_maximo' => $request->cupo_maximo,
                    'estado' => 0,
                    'tipo_sensor' => $request->tipo_sensor,
                    'codigo_sensor' => $request->codigo_sensor,
                    'usuario_registra' => $request->usuario_registra,
                ]);
                $tarjeta->save();
                return response()->json([
                    'status' => 201,
                    'message' => 'Tarjeta beer creada correctamente.',
                    'data' => $tarjeta
                ], 201);
            }
        } catch (AuthorizationException $th) {
            return response()->json([
                'status' => $th->getCode(),
                'message' => MENSAJE_NO_AUTORIZADO,
                'data' => $th->getMessage()
            ], 401);
        } catch (Exception $e) {
            return response()->json([
                'status' => $e->getCode(),
                'message' => MENSAJE_ERROR2,
                'data' => $e->getMessage()
            ], 400);
        }
    }

    public function escanearSensor(Request $request)
    {
        try {
                $validator = Validator::make($request->query(), [
                    'id_maquina' => 'required|exists:maquina,id',
                    // 'codigo_sensor' => 'required|exists:pulsera,codigo_sensor',
                    'codigo_sensor' => 'required',
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'status' => 422,
                        'message' => MENSAJE_ERROR,
                        'data' => $validator->errors()
                    ], 422);
                }else{
                    $maquina = Maquina::where('id', $request->id_maquina)->first();
                    $sensor = Pulsera::where('codigo_sensor', $request->codigo_sensor)->first();
                    // Existe la maquina en BBDD
                    if($maquina){
                        // Existe el sensor en BBDD
                        if($sensor){
                            // Si la maquina esta habilitada
                            if(intval($maquina->estado) === 1){
                                // Si el sensor esta habilitado
                                if(intval($sensor->estado) === 1){
                                    // Validar la cantidad maxima que puede obtener el usuario
                                    if($sensor->cupo_maximo < $maquina->cantidad){
                                        return response()->json([
                                            'status' => 202,
                                            // 'message' => 'Sensor habilitado.',
                                            'data' => $sensor->cupo_maximo
                                        ], 202)->content();
                                    }else{
                                        return response()->json([
                                            'status' => 202,
                                            // 'message' => 'Maquina habilitada.',
                                            'data' => $maquina->cantidad
                                        ], 202)->content();
                                    }
                                // Si el sensor esta deshabilitado
                                }elseif(intval($sensor->estado) === 0){
                                    $maquina->codigo_sensor = $sensor->codigo_sensor;
                                    $maquina->save();
                                    return response()->json([
                                        'status' => 200,
                                        // 'message' => 'Alerta: Sensor enviada.',
                                        'data' => 'Alerta: Sensor enviado.'
                                    ], 200)->content();
                                }elseif(intval($sensor->estado) === 2 || intval($sensor->estado) === 3){
                                    $litros = $sensor->cupo_maximo/$maquina->precio;
                                    return response()->json([
                                        'status' => 202,
                                        // 'message' => 'Maquina habilitada.',
                                        'data' => $litros
                                    ], 202)->content();
                                }
                            // Si la maquina es caja
                            }elseif(intval($maquina->estado) === 3){
                                $maquina->codigo_sensor = $sensor->codigo_sensor;
                                $maquina->save();
                                return response()->json([
                                    'status' => 200,
                                    // 'message' => 'Alerta: Sensor enviada.',
                                    'data' => 'Alerta: Sensor enviado.'
                                ], 200)->content();
                            // Si la maquina esta deshabilitada o ne mantenimiento
                            }else{
                                return response()->json([
                                    'status' => 201,
                                    'message' => 'Maquina deshabilitada.',
                                ], 201)->content();
                            }
                        // Si el sensor no existe
                        }else{
                            // Si la maquina es caja
                            if(intval($maquina->estado) === 3){
                                //  crear registro de sensor
                                $newRequest = new Request(['codigo_sensor' => $request->codigo_sensor, 'tipo_sensor' => 'RFID']);
                                $response = $this->crearBeerCode($newRequest);
                                // Devolver los resultados
                                return response()->json([
                                    'status' => 200,
                                    'data' => $response
                                ]);
                            // Si el sensor no existe
                            }else{
                                return response()->json([
                                    'status' => 404,
                                    'message' => 'Sensor no encontrado.',
                                ], 404)->content();
                            }
                        }
                    // Si la maquina no existe
                    }else{
                        return response()->json([
                            'status' => 404,
                            'message' => 'Maquina no encontrada.',
                        ], 404)->content();
                    }
                }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => $th->getCode(),
                'message' => MENSAJE_ERROR2,
                'data' => $th->getMessage()
            ], 400)->content();
        }
    }

    public function asignarTarjeta(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id_cliente' => 'required|exists:usuarios,id',
                'codigo_sensor' => 'required|exists:pulsera,codigo_sensor',
                'usuario_registra' => 'required|exists:usuarios,id'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => 422,
                    'message' => MENSAJE_ERROR,
                    'data' => $validator->errors()
                ], 422);
            } else {
                $registro = Pulsera::where('codigo_sensor', $request->codigo_sensor)->first();
                if ($registro) {
                    $usuario = Usuario::where('id', $request->id_cliente)->first();
                    if ($usuario) {
                        // Asigna el ID del usuario a la tarjeta
                        $registro->id_cliente = $usuario->id;
                        $registro->cupo_maximo = $request->cupo_maximo;
                        $registro->estado = $request->estado;
                        $registro->usuario_registra = $request->usuario_registra;
                        // Guarda la tarjeta en la base de datos con el usuario vinculado
                        $registro->save();

                        if($request->estado === '2' || $request->estado === '3'){
                            $venta = new Venta();
                            $venta->id_cliente = $request->id_cliente;
                            $venta->total = 0;
                            $venta->precio = $request->cupo_maximo;
                            $venta->tipo_pago = 'efectivo';
                            $venta->estado = 2 ;
                            $venta->save();
                        }

                        $tarjeta = Pulsera::where('id', $registro->id)->with(['usuario'])->first();

                        return response()->json([
                            'status' => 201,
                            'message' => 'Usuario vinculado a la tarjeta exitosamente.',
                            'data' => $tarjeta,
                        ], 201);
                    } else {
                        return response()->json([
                            'status' => 404,
                            'message' => 'Usuario no encontrado.',
                        ], 404);
                    }
                } else {
                    return response()->json([
                        'status' => 404,
                        'message' => 'Tarjeta no encontrada.',
                    ], 404);
                }
            }
        } catch (AuthorizationException $th) {
            return response()->json([
                'status' => $th->getCode(),
                'message' => MENSAJE_NO_AUTORIZADO,
                'data' => $th->getMessage()
            ], 401);
        } catch (Exception $e) {
            return response()->json([
                'status' => $e->getCode(),
                'message' => MENSAJE_ERROR2,
                'data' => $e->getMessage()
            ], 400);
        }
    }

    public function limpiarTarjeta(Request $request)
    {
        try {
            $validator = Validator::make($request->query(), [
                'id_pulsera' => 'required|exists:pulsera,id',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => 422,
                    'message' => MENSAJE_ERROR,
                    'data' => $validator->errors()
                ], 422);
            } else {
                $registro = Pulsera::where('id', $request->id_pulsera)->first();
                if ($registro) {
                        if($registro->estado === 2 || $registro->estado === 3){
                            $consumos = Consumo::where('id_cliente', $registro->id_cliente)->where('estado', 2)->get();
                            if($consumos->count() > 0){
                                foreach ($consumos as $consumo) {
                                    $consumo->estado = 1;
                                    $consumo->save();
                                }
                            }
                            $venta = Venta::where('id_cliente', $registro->id_cliente)->where('estado', 2)->first();
                            if($venta){
                                $venta->estado = 1;
                                $venta->save();
                            }
                        }
                        // borra el ID del usuario a la tarjeta
                        $registro->id_cliente = null;
                        $registro->cupo_maximo = 0;
                        $registro->estado = 0;
                        $registro->usuario_registra = null;
                        // Guarda la tarjeta en la base de datos con el usuario vinculado
                        $registro->save();
                        return response()->json([
                            'status' => 201,
                            'message' => 'Sensor vaciado con éxito.',
                            'data' => $registro,
                        ], 201);

                } else {
                    return response()->json([
                        'status' => 404,
                        'message' => 'Sensor no encontrado.',
                    ], 404);
                }
            }
        } catch (AuthorizationException $th) {
            return response()->json([
                'status' => $th->getCode(),
                'message' => MENSAJE_NO_AUTORIZADO,
                'data' => $th->getMessage()
            ], 401);
        } catch (Exception $e) {
            return response()->json([
                'status' => $e->getCode(),
                'message' => MENSAJE_ERROR2,
                'data' => $e->getMessage()
            ], 400);
        }
    }

    public function listadoTarjetas()
    {
        try {
            // Obtiene todos los registros de la tabla pulsera
            $tarjetas = Pulsera::with(['usuario', 'usuario_registra', 'usuario.ventas'])->get();
            return ($tarjetas->count() > 0) ?
                response()->json([
                    'status' => 200,
                    'message' => 'Listado de tarjetas.',
                    'data' => $tarjetas
                ], 200) :
                response()->json([
                    'status' => 201,
                    'message' => 'No existen tarjetas.',
                    'data' => null
                ], 200);
        } catch (AuthorizationException $th) {
            return response()->json([
                'status' => $th->getCode(),
                'message' => MENSAJE_NO_AUTORIZADO,
                'data' => $th->getMessage()
            ], 401);
        } catch (Exception $e) {
            return response()->json([
                'status' => $e->getCode(),
                'message' => 'Ocurrió un error!.',
                'data' => $e->getMessage()
            ], 400);
        }
    }

    public function listadoUsuariosBeer()
    {
        try {
            $usuarios = Usuario::where('rol', 'BEER')->get();
            // Obtiene todos los registros de la tabla pulsera
            return ($usuarios->count() > 0) ?
                response()->json([
                    'status' => 200,
                    'message' => 'Listado de usuarios Beer.',
                    'data' => $usuarios
                ], 200) :
                response()->json([
                    'status' => 201,
                    'message' => 'No existen usuarios Beer.',
                    'data' => null
                ], 200);
        } catch (AuthorizationException $th) {
            return response()->json([
                'status' => $th->getCode(),
                'message' => MENSAJE_NO_AUTORIZADO,
                'data' => $th->getMessage()
            ], 401);
        } catch (Exception $e) {
            return response()->json([
                'status' => $e->getCode(),
                'message' => 'Ocurrió un error!.',
                'data' => $e->getMessage()
            ], 400);
        }
    }

    public function listadoMaquinas()
    {
        try {
            // Obtiene todos los registros de la tabla maquina
            $maquinas = Maquina::all();
            return ($maquinas->count() > 0) ?
                response()->json([
                    'status' => 200,
                    'message' => 'Listado de maquinas.',
                    'data' => $maquinas
                ], 200) :
                response()->json([
                    'status' => 201,
                    'message' => 'No existen maquinas.',
                    'data' => null
                ], 200);
        } catch (AuthorizationException $th) {
            return response()->json([
                'status' => $th->getCode(),
                'message' => MENSAJE_NO_AUTORIZADO,
                'data' => $th->getMessage()
            ], 401);
        } catch (Exception $e) {
            return response()->json([
                'status' => $e->getCode(),
                'message' => 'Ocurrió un error!.',
                'data' => $e->getMessage()
            ], 400);
        }
    }

    public function activarMaquina(Request $request)
    {
        try {
            $validator = Validator::make($request->query(), [
                'id_maquina' => 'required|exists:maquina,id',
                'estado' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => 422,
                    'message' => MENSAJE_ERROR,
                    'data' => $validator->errors()
                ], 422);
            } else {
                $maquina = Maquina::where('id', $request->id_maquina)->first();

                if ($maquina) {
                        // Asigna el ID del usuario a la tarjeta
                        $maquina->estado = $request->estado;
                        // Guarda la tarjeta en la base de datos con el usuario vinculado
                        $maquina->save();

                        return response()->json([
                            'status' => 201,
                            'message' => 'Maquina encendida.',
                            'data' => true
                        ], 201);

                } else {
                    return response()->json([
                        'status' => 404,
                        'message' => 'Maquina no encontrada.',
                    ], 404);
                }
            }
        } catch (AuthorizationException $th) {
            return response()->json([
                'status' => $th->getCode(),
                'message' => MENSAJE_NO_AUTORIZADO,
                'data' => $th->getMessage()
            ], 401);
        } catch (Exception $e) {
            return response()->json([
                'status' => $e->getCode(),
                'message' => MENSAJE_ERROR2,
                'data' => $e->getMessage()
            ], 400);
        }
    }

    public function verMaquina(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id_maquina' => 'required|exists:maquina,id',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => 422,
                    'message' => MENSAJE_ERROR,
                    'data' => $validator->errors()
                ], 422);
            } else {
                $maquina = Maquina::where('id', $request->id_maquina)->first();
                if ($maquina) {
                        return response()->json([
                            'status' => 201,
                            'message' => 'Maquina encontrada.',
                            'data' => $maquina,
                        ], 201);

                } else {
                    return response()->json([
                        'status' => 404,
                        'message' => 'Maquina no encontrada.',
                    ], 404);
                }
            }
        } catch (AuthorizationException $th) {
            return response()->json([
                'status' => $th->getCode(),
                'message' => MENSAJE_NO_AUTORIZADO,
                'data' => $th->getMessage()
            ], 401);
        } catch (Exception $e) {
            return response()->json([
                'status' => $e->getCode(),
                'message' => MENSAJE_ERROR2,
                'data' => $e->getMessage()
            ], 400);
        }
    }

    public function borrarSensorMaquina(Request $request)
    {
        try {
                $validator = Validator::make($request->all(), [
                    'codigo_sensor' => 'required|exists:pulsera,codigo_sensor',
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'status' => 422,
                        'message' => MENSAJE_ERROR,
                        'data' => $validator->errors()
                    ], 422);
                }else{
                    $maquina = Maquina::where('codigo_sensor', $request->codigo_sensor)->first();
                    if($maquina->codigo_sensor){
                        $maquina->codigo_sensor = '';
                        $maquina->save();
                        return response()->json([
                            'status' => 200,
                            'message' => 'Sensor borrado.',
                            'data' => $maquina
                        ], 200);
                    }else{
                        return response()->json([
                            'status' => 404,
                            'message' => 'Maquina no encontrada.',
                            'data' => null
                        ], 404);
                    }
                }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => $th->getCode(),
                'message' => MENSAJE_ERROR2,
                'data' => $th->getMessage()
            ], 400);
        }
    }

    public function crearConsumo(Request $request)
    {
        try {
            $validator = Validator::make($request->query(), [
                'id_beer' => 'required|exists:pulsera,codigo_sensor',
                'total' => 'required',
                'id_maquina' => 'required|exists:maquina,id',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => 422,
                    'message' => MENSAJE_ERROR,
                    'data' => $validator->errors()
                ], 422);
            } else {
                $sensor = Pulsera::where('codigo_sensor', $request->id_beer)->first();
                $maquina = Maquina::where('id', $request->id_maquina)->first();
                // Cuando la maquina esta activa
                if($maquina->estado === 1){
                    if($sensor->id_cliente){
                        //cuando la pulsera esta activa y es postpago
                        if($sensor->estado === 1 || $sensor->estado === 4){
                            //Actualizar el saldo de la pulsera
                            $sensor->cupo_maximo = round($sensor->cupo_maximo - $request->total, 2);
                            //condicion cuando se acaba el saldo
                            if($sensor->cupo_maximo <= 0.1){
                                $sensor->cupo_maximo = 0;
                                $sensor->estado = 0;
                            }
                            $sensor->save();
                            //Actualizar la cantidad de la maquina
                            $maquina->cantidad = round($maquina->cantidad - $request->total, 2);
                            //condicion cuando se acaba la cerveza en la maquina
                            if($maquina->cantidad <= 0.1){
                                $maquina->cantidad = 0;
                                $maquina->estado = 2;
                            }
                            $maquina->save();
                            //Crear el consumo
                            $tarjeta = new Consumo([
                                'id_cliente' => $sensor->id_cliente,
                                'total' => round($request->total, 2),
                                'precio' => round($maquina->precio * $request->total, 2),
                                'id_maquina' => $request->id_maquina,
                                'estado' => 0,
                            ]);
                            $tarjeta->save();
                            return response()->json([
                                'status' => 201,
                                'message' => 'Consumo beer creado correctamente.',
                                'data' => $tarjeta
                            ], 201);
                        //cuando la pulsera esta activa, es prepago o es mixta
                        }elseif($sensor->estado === 2 || $sensor->estado === 3){
                            //Actualizar el saldo de la pulsera
                            $sensor->cupo_maximo = round($sensor->cupo_maximo - ($request->total * $maquina->precio), 2);
                            //condicion cuando se acaba el saldo y es prepago
                            if($sensor->cupo_maximo <= 0.1 && $sensor->estado === 2){
                                $sensor->cupo_maximo = 0;
                                // $sensor->estado = 2;
                            //condicion cuando se acaba el saldo y es mixta
                            }else if($sensor->cupo_maximo <= 0.1 && $sensor->estado === 3){
                                $sensor->cupo_maximo = 4;
                                $sensor->estado = 4;
                            }
                            $sensor->save();
                            //Actualizar la cantidad de la maquina
                            $maquina->cantidad = round($maquina->cantidad - $request->total, 2);
                            //condicion cuando se acaba la cerveza en la maquina
                            if($maquina->cantidad <= 0.1){
                                $maquina->cantidad = 0;
                                $maquina->estado = 2;
                            }
                            $maquina->save();
                            //Actualizar la venta del usuario
                            $venta = Venta::where('id_cliente', $sensor->id_cliente)->where('estado', 2)->first();
                            if($venta){
                                $venta->total = round($venta->total + $request->total, 2);
                                $venta->save();
                                //Crear el consumo
                                $tarjeta = new Consumo([
                                    'id_cliente' => $sensor->id_cliente,
                                    'total' => round($request->total, 2),
                                    'precio' => round($maquina->precio * $request->total, 2),
                                    'id_maquina' => $request->id_maquina,
                                    'estado' => 2,
                                    'id_venta'=> $venta->id
                                ]);
                                $tarjeta->save();
                                return response()->json([
                                    'status' => 201,
                                    'message' => 'Consumo beer creado correctamente.',
                                    'data' => $tarjeta
                                ], 201);
                            }else{
                                return response()->json([
                                    'status' => 404,
                                    'message' => 'Tarjeta no asignada.',
                                    'data' => null
                                ], 404);
                            }
                        //cuando la pulsera esta desactivada
                        }else{
                            return response()->json([
                                'status' => 404,
                                'message' => 'Tarjeta no asignada.',
                                'data' => null
                            ], 404);
                        }
                    }else{
                        return response()->json([
                            'status' => 404,
                            'message' => 'Tarjeta no asignada',
                            'data' => null
                        ], 404);
                    }
                } else {
                    return response()->json([
                        'status' => 201,
                        'message' => 'Maquina deshabilitada.',
                    ], 201);
                }
            }
        } catch (AuthorizationException $th) {
            return response()->json([
                'status' => $th->getCode(),
                'message' => MENSAJE_NO_AUTORIZADO,
                'data' => $th->getMessage()
            ], 401);
        } catch (Exception $e) {
            return response()->json([
                'status' => $e->getCode(),
                'message' => MENSAJE_ERROR2,
                'data' => $e->getMessage()
            ], 400);
        }
    }

    public function listarConsumos(Request $request)
    {
        try {
            $idCliente = $request->input('id_cliente');
            // Validar la presencia del ID de pulsera
            if (!$idCliente) {
                return response()->json([
                    'error' => 'El campo id_cliente es obligatorio.'
                ], 400);
            }

            try {
                $pulsera = Pulsera::where('id_cliente', $idCliente)->first();
                if (!$pulsera) {
                    return response()->json([
                        'error' => 'No se encontró la pulsera.'
                    ], 404);
                }
                $consumos = Consumo::where('id_cliente', $idCliente)
                   ->whereIn('estado', [0, 2])
                   ->with(['maquina'])
                   ->get();
                return response()->json([
                    'data' => $consumos
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'error' => 'Error al obtener los consumos.',
                    'message' => $e->getMessage()
                ], 500);
            }
        } catch (AuthorizationException $th) {
            return response()->json([
                'status' => $th->getCode(),
                'message' => MENSAJE_NO_AUTORIZADO,
                'data' => $th->getMessage()
            ], 401);
        } catch (Exception $e) {
            return response()->json([
                'status' => $e->getCode(),
                'message' => MENSAJE_ERROR2,
                'data' => $e->getMessage()
            ], 400);
        }
    }

    public function pagarVentas(Request $request){
        try {
            $idCliente = $request->input('id_cliente');
            // Validar la presencia del ID de pulsera
            if (!$idCliente) {
                return response()->json([
                    'error' => 'El campo id_cliente es obligatorio.'
                ], 400);
            }

            try {
                //obtener la pulsera donde esta registrado el usuario
                $pulsera = Pulsera::where('id_cliente', $idCliente)->first();
                if (!$pulsera) {
                    return response()->json([
                        'error' => 'No se encontró la pulsera.'
                    ], 404);
                }
                if($pulsera->estado === 1){
                    $consumos = Consumo::where('id_cliente', $idCliente)
                                    ->where('estado', 0)
                                    ->get();
                    // Obtener los consumos (como array)
                    $consumosArray = $consumos->toArray();
                    // Obtener la suma de los totales y precios
                    $total = array_reduce($consumosArray, function ($carry, $item) {
                        return $carry + $item['total'];
                    });
                    $precio = array_reduce($consumosArray, function ($carry, $item) {
                        return $carry + $item['precio'];
                    });
                    // Crear la venta
                    $venta = new Venta();
                    $venta->id_cliente = $pulsera->id_cliente;
                    $venta->total = $total;
                    $venta->precio = $precio;
                    $venta->tipo_pago = 'efectivo';
                    $venta->estado = 1;
                    $venta->save();
                    // Actualizar el campo id_venta de los consumos
                    foreach ($consumos as $consumo) {
                        $consumo->id_venta = $venta->id;
                        $consumo->estado = 1;
                        $consumo->save();
                    }
                }elseif($pulsera->estado === 4){
                    $consumosPP = Consumo::where('id_cliente', $idCliente)
                                    ->where('estado', 0)
                                    ->get();
                    // Obtener los consumos (como array)
                    $consumosArray = $consumosPP->toArray();
                    // Obtener la suma de los totales y precios
                    $total = array_reduce($consumosArray, function ($carry, $item) {
                        return $carry + $item['total'];
                    });
                    $precio = array_reduce($consumosArray, function ($carry, $item) {
                        return $carry + $item['precio'];
                    });
                    $venta = Venta::where('id_cliente', $pulsera->id_cliente)->where('estado', 2)->first();
                    if($venta){
                        $venta->total = $venta->total + $total;
                        $venta->precio = $venta->precio + $precio;
                        $venta->estado = 1;
                        $venta->save();
                    }
                    // Actualizar el campo id_venta de los consumos
                    foreach ($consumosPP as $consumo) {
                        $consumo->id_venta = $venta->id;
                        $consumo->estado = 1;
                        $consumo->id_venta = $venta->id;
                        $consumo->save();
                    }
                    $consumos = Consumo::where('id_cliente', $idCliente)
                                    ->where('estado', 2)
                                    ->get();
                    // Actualizar el campo id_venta de los consumos
                    foreach ($consumos as $consumo) {
                        $consumo->id_venta = $venta->id;
                        $consumo->estado = 1;
                        $consumo->save();
                    }
                }
                // Vaciar la pulsera
                 // Obtener el codigo_sensor del request original
                $newRequest = new Request(['id_pulsera' => $pulsera->id]); // Crear un nuevo objeto Request
                $response =$this->limpiarTarjeta($newRequest); // Pasar el nuevo objeto Request

                // Devolver los resultados
                return response()->json([
                    'status' => 200,
                    'data' => $response
                ]);

            } catch (\Exception $e) {
                return response()->json([
                    'error' => 'Error al obtener los consumos.',
                    'message' => $e->getMessage()
                ], 500);
            }
        } catch (AuthorizationException $th) {
            return response()->json([
                'status' => $th->getCode(),
                'message' => MENSAJE_NO_AUTORIZADO,
                'data' => $th->getMessage()
            ], 401);
        } catch (Exception $e) {
            return response()->json([
                'status' => $e->getCode(),
                'message' => MENSAJE_ERROR2,
                'data' => $e->getMessage()
            ], 400);
        }
    }
}
