<?php

namespace App\Http\Controllers;

use App\Models\Maquina;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
define('MENSAJE_NO_AUTORIZADO', 'No autorizado!.');
define('MENSAJE_ERROR2', 'Ocurrio un error!.');
class MaquinaController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth:api');
    }
    public function crearMaquina(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'tipo_cerveza' => 'required',
                'ubicacion' => 'required',
                'precio' => 'required',
                'cantidad' => 'required',
                'estado' => 'required',
                'id_comercio' => 'required'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => 422,
                    'message' => 'Error al validar los datos de entrada.',
                    'data' => $validator->errors()
                ], 422);
            } else {
                $maquina = Maquina::create([
                    'tipo_cerveza' => $request->tipo_cerveza,
                    'ubicacion' => $request->ubicacion,
                    'precio' => $request->precio,
                    'cantidad' => $request->cantidad,
                    'estado' => 0,
                    'id_comercio' => $request->id_comercio,
                ]);
                return response()->json([
                    'status' => 201,
                    'message' => 'Maquina creado correctamente.',
                    'data' => $maquina
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

    public function verMaquina($id)
    {
        try {
            if (Str::isUuid($id)) {
                $maquina = Maquina::get()->where('id', $id)->first();
                if ($maquina != null) {
                    return response()->json([
                        'status' => 200,
                        'message' => 'Datos de Maquina.',
                        'data' => $maquina
                    ]);
                } else {
                    return response()->json([
                        'status' => 200,
                        'message' => 'No se encontro datos del Maquina indicado.',
                        'data' => null
                    ]);
                }
            } else {
                $maquina = Maquina::get()->where('id', $id)->first();
                if ($maquina != null) {
                    return response()->json([
                        'status' => 200,
                        'message' => 'Datos de Maquina.',
                        'data' => $maquina
                    ]);
                } else {
                    return response()->json([
                        'status' => 200,
                        'message' => 'No se encontro datos del Maquina indicado.',
                        'data' => null
                    ]);
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

    public function listarMaquina(Request $request)
    {
        try {
            $request->merge([
                'page' => $request->input('page', 0) + 1
            ]);
            $lst_usuarios = Maquina::get();
            $perPage = 10; // Número de elementos por página
            $page = request()->get('page', 1); // Número de página actual
            $offset = ($page - 1) * $perPage; // Cálculo del offset

            $lst_usuarios = collect($lst_usuarios)->forPage($page, $perPage); // Paginación de la colección

            if ($lst_usuarios != null) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Lista de Usuarios.',
                    'data' => $lst_usuarios
                ]);
            } else {
                return response()->json([
                    'status' => 200,
                    'message' => 'No hay Usuarios registrados.',
                    'data' => null
                ]);
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


    public function actualizarMaquina(Request $request, $id)
    {
        try {
            if ($id) {
                $maquina = Maquina::get()->where('id', $id)->first();
                if ($maquina != null) {
                    $maquina->fill($request->all());
                    $maquina->save();
                    return response()->json([
                        'status' => 200,
                        'message' => 'Maquina actualizado correctamente.',
                        'data' => $maquina
                    ]);
                } else {
                    return response()->json([
                        'status' => 200,
                        'message' => 'No se encontro datos del Maquina indicado.',
                        'data' => null
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 200,
                    'message' => 'No se encontro el Maquina indicado.',
                    'data' => null
                ]);
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

    public function eliminarMaquina($id)
    {
        try {
            if ($id) {
                $maquina = Maquina::get()->where('id', $id)->first();
                if ($maquina != null) {
                    $maquina->delete();
                    return response()->json([
                        'status' => 200,
                        'message' => 'Maquina eliminado correctamente.',
                        'data' => null
                    ]);
                } else {
                    return response()->json([
                        'status' => 200,
                        'message' => 'No se encontro datos del Maquina indicado.',
                        'data' => null
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 200,
                    'message' => 'No se encontro el Maquina indicado.',
                    'data' => null
                ]);
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
