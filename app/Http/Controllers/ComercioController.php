<?php

namespace App\Http\Controllers;

use App\Models\Comercio;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ComercioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function crearComercio(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nombre_comercial' => 'required',
                'razon_social' => 'required',
                'ruc' => 'required|unique:comercio,ruc',
                'direccion' => 'required',
                'telefono' => 'required',
                'whatsapp' => 'required',
                'correo' => 'required|unique:comercio,correo',
                'logo' => 'required',
                'sitio_web' => 'required',
                'estado' => 'nullable',
                'tipo_comercio' => 'required|exists:tipo_comercio,id'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => 422,
                    'message' => 'Error al validar los datos de entrada.',
                    'data' => $validator->errors()
                ], 422);
            } else {
                $comercio = Comercio::create([
                    'nombre_comercial' => $request->nombre_comercial,
                    'razon_social' => $request->razon_social,
                    'ruc' => $request->ruc,
                    'direccion' => $request->direccion,
                    'telefono' => $request->telefono,
                    'whatsapp' => $request->whatsapp,
                    'correo' => $request->correo,
                    'logo' => $request->logo,
                    'sitio_web' => $request->sitio_web,
                    'tipo_comercio' => $request->tipo_comercio,
                    'estado' => true
                ]);
                return response()->json([
                    'status' => 201,
                    'message' => 'Comercio creado correctamente.',
                    'data' => $comercio
                ], 201);
            }

        } catch (AuthorizationException $th) {
            return response()->json([
                'status' => $th->getCode(),
                'message' => 'No autorizado!.',
                'data' => $th->getMessage()
            ], 401);
        } catch (Exception $e) {
            return response()->json([
                'status' => $e->getCode(),
                'message' => 'Ocurrio un error!.',
                'data' => $e->getMessage()
            ], 400);
        }
    }

    public function verComercio($id)
    {
        try {
            if (Str::isUuid($id)) {
                $comercio = Comercio::find($id);
                return ($comercio != null) ?
                    response()->json([
                        'status' => 200,
                        'message' => 'Solicitud de Firma previa indicado.',
                        'data' => $comercio
                    ], 200) :
                    response()->json([
                        'status' => 200,
                        'message' => 'No se encontro la Solicitud de Firma previa.',
                        'data' => null
                    ], 200);
            } else {
                return response()->json([
                    'status' => 422,
                    'message' => 'El id ingresado es incorrecto.',
                    'data' => 'UUID Formato incorrecto.'
                ], 422);
            }

        } catch (AuthorizationException $th) {
            return response()->json([
                'status' => $th->getCode(),
                'message' => 'No autorizado!.',
                'data' => $th->getMessage()
            ], 401);
        } catch (Exception $e) {
            return response()->json([
                'status' => $e->getCode(),
                'message' => 'Ocurrio un error!.',
                'data' => $e->getMessage()
            ], 400);
        }
    }

    public function actualizarComercio(Request $request, $id)
    {
        try {
            $comercio = Comercio::find($id);
            if ($comercio != null) {
                $comercio->update($request->all());
                return response()->json([
                    'status' => 200,
                    'message' => 'Información comercio actualizada correctamente.',
                    'data' => $comercio
                ], 200);
            } else {
                return response()->json([
                    'status' => 200,
                    'message' => 'No se encontro el comercio indicado.',
                    'data' => null
                ], 200);
            }
        } catch (AuthorizationException $th) {
            return response()->json([
                'status' => $th->getCode(),
                'message' => 'No autorizado!.',
                'data' => $th->getMessage()
            ], 401);
        } catch (Exception $e) {
            return response()->json([
                'status' => $e->getCode(),
                'message' => 'Ocurrio un error!.',
                'data' => $e->getMessage()
            ], 400);
        }
    }

    public function eliminarComercio($id)
    {
        try {
            $comercio = Comercio::find($id);
            if ($comercio != null) {
                $comercio->update([
                    'estado' => false
                ]);
                return response()->json([
                    'status' => 200,
                    'message' => 'Entidad Comercial eliminada correctamente.',
                    'data' => null
                ], 200);
            } else {
                return response()->json([
                    'status' => 200,
                    'message' => 'No se encontro la Entidad Comercial indicada.',
                    'data' => null
                ], 200);
            }
        } catch (AuthorizationException $th) {
            return response()->json([
                'status' => $th->getCode(),
                'message' => 'No autorizado!.',
                'data' => $th->getMessage()
            ], 401);
        } catch (Exception $e) {
            return response()->json([
                'status' => $e->getCode(),
                'message' => 'Ocurrio un error!.',
                'data' => $e->getMessage()
            ], 400);
        }
    }

    public function listarAllComercios()
    {
        try {
            $lst_comercios = Comercio::all()->where('estado', true);
            if ($lst_comercios != null) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Lista de entidades comerciales. ',
                    'data' => $lst_comercios
                ]);
            } else {
                return response()->json([
                    'status' => 200,
                    'message' => 'No existen entidades comerciales',
                    'data' => $lst_comercios
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => $th->getCode(),
                'message' => 'Ocurrio un error!. ',
                'data' => $th->getMessage()
            ], $th->getCode());
        }
    }
}