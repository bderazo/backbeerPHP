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
                    'codigo_sensor' => 'required|exists:pulsera,codigo_sensor',
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
                    if($maquina){
                        if($sensor){
                            if(intval($maquina->estado) === 1){
                                if(intval($sensor->estado) === 1){
                                    if($sensor->cupo_maximo < $maquina->cantidad){
                                        return response()->json([
                                            'status' => 202,
                                            // 'message' => 'Sensor habilitado.',
                                            'data' => $sensor->cupo_maximo
                                        ], 202)->content();
                                    }else{
                                        return response()->json([
                                            'status' => 203,
                                            // 'message' => 'Maquina habilitada.',
                                            'data' => $maquina->cantidad
                                        ], 203)->content();
                                    }
                                }elseif(intval($sensor->estado) === 0){
                                    $maquina->codigo_sensor = $sensor->codigo_sensor;
                                    $maquina->save();
                                    return response()->json([
                                        'status' => 200,
                                        // 'message' => 'Alerta: Sensor enviada.',
                                        'data' => 'Alerta: Sensor enviado.'
                                    ], 200)->content();
                                }
                            }else{
                                return response()->json([
                                    'status' => 201,
                                    'message' => 'Maquina deshabilitada.',
                                ], 201)->content();
                            }
                        }else{
                            return response()->json([
                                'status' => 404,
                                'message' => 'Sensor no encontrado.',
                            ], 404)->content();
                        }
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
                        $registro->cupo_maximo = 4;
                        $registro->estado = 1;
                        $registro->usuario_registra = $request->usuario_registra;
                        // Guarda la tarjeta en la base de datos con el usuario vinculado
                        $registro->save();

                        return response()->json([
                            'status' => 201,
                            'message' => 'Usuario vinculado a la tarjeta exitosamente.',
                            'data' => $registro,
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
                        // Asigna el ID del usuario a la tarjeta
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
            $tarjetas = Pulsera::with(['usuario', 'usuario_registra'])->get();
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
                    if($sensor->id_cliente && $maquina->estado){
                        $sensor->cupo_maximo = round($sensor->cupo_maximo, 2) - round($request->total, 2);
                        $sensor->save();
                        $maquina->cantidad = round($maquina->cantidad, 2) - round($request->total, 2);
                        $maquina->save();
                        $tarjeta = new Consumo([
                            'id_pulsera' => $sensor->id,
                            'total' => round($request->total, 2),
                            'precio' => round($maquina->precio, 2) * round($request->total, 2),
                            'id_maquina' => $request->id_maquina,
                            'estado' => 0,
                        ]);
                        $tarjeta->save();
                        return response()->json([
                            'status' => 201,
                            'message' => 'Venta beer creada correctamente.',
                            'data' => $tarjeta
                        ], 201);
                    }else{
                        return response()->json([
                            'status' => 404,
                            'message' => 'Tarjeta no asignada o maquina no encontrada.',
                            'data' => null
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

    public function listarConsumos(Request $request)
    {
        try {
            $idPulsera = $request->input('id_pulsera');
            // Validar la presencia del ID de pulsera
            if (!$idPulsera) {
                return response()->json([
                    'error' => 'El campo id_pulsera es obligatorio.'
                ], 400);
            }

            try {
                $pulsera = Pulsera::where('id', $idPulsera)->first();
                if (!$pulsera) {
                    return response()->json([
                        'error' => 'No se encontró la pulsera.'
                    ], 404);
                }
                $consumos = Consumo::where('id_pulsera', $idPulsera)
                                    ->where('estado', 0)
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
            $idPulsera = $request->input('id_pulsera');
            // Validar la presencia del ID de pulsera
            if (!$idPulsera) {
                return response()->json([
                    'error' => 'El campo id_pulsera es obligatorio.'
                ], 400);
            }

            try {
                $pulsera = Pulsera::where('id', $idPulsera)->first();
                if (!$pulsera) {
                    return response()->json([
                        'error' => 'No se encontró la pulsera.'
                    ], 404);
                }
                $consumos = Consumo::where('id_pulsera', $idPulsera)
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
                $venta->tipo_pago = 1;
                $venta->estado = 'efectivo';
                $venta->save();

                // Actualizar el campo id_venta de los consumos
                foreach ($consumos as $consumo) {
                    $consumo->id_venta = $venta->id;
                    $consumo->estado = 1;
                    $consumo->save();
                }

                // Vaciar la pulsera
                 // Obtener el codigo_sensor del request original
                $newRequest = new Request(['id_pulsera' => $idPulsera]); // Crear un nuevo objeto Request
                $response =$this->limpiarTarjeta($newRequest); // Pasar el nuevo objeto Request

                // Devolver los resultados
                return response()->json([
                    'data' => $venta
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
