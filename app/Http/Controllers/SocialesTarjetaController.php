<?php

namespace App\Http\Controllers;

use App\Models\SocialesTarjeta;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SocialesTarjetaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function crearSocialesTarjeta(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'tarjeta_id' => 'required|exists:user_tarjeta,id',
                'estado' => 'nullable',
                'text_label' => 'nullable',
                'url_label' => 'nullable',
                'tipo_social' => 'nullable',
                'icon_social' => 'nullable',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => 422,
                    'message' => 'Error al validar los datos de entrada.',
                    'data' => $validator->errors()
                ], 422);
            } else {
                $sociales = SocialesTarjeta::create([
                    'tarjeta_id' => $request->tarjeta_id,
                    'estado' => true,
                    'text_label' => $request->text_label,
                    'url_label' => $request->url_label,
                    'tipo_social' => $request->tipo_social,
                    'icon_social' => $request->icon_social,
                ]);
                return response()->json([
                    'status' => 201,
                    'message' => 'Sociales de Tarjeta creado correctamente.',
                    'data' => $sociales
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

    public function actualizarSocialesTarjeta(Request $request, $id)
    {
        try {
            $sociales = SocialesTarjeta::where('tarjeta_id', $id)->first();
            if ($sociales != null) {
                $sociales->update($request->all());
                return response()->json([
                    'status' => 200,
                    'message' => 'Información social de tarjeta actualizada correctamente.',
                    'data' => $sociales
                ], 200);
            } else {
                return response()->json([
                    'status' => 200,
                    'message' => 'No se encontro la informacion social indicada.',
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

    // public function verComercio($id)
    // {
    //     try {
    //         if (Str::isUuid($id)) {
    //             $comercio = Comercio::find($id);
    //             return ($comercio != null) ?
    //                 response()->json([
    //                     'status' => 200,
    //                     'message' => 'Solicitud de Firma previa indicado.',
    //                     'data' => $comercio
    //                 ], 200) :
    //                 response()->json([
    //                     'status' => 200,
    //                     'message' => 'No se encontro la Solicitud de Firma previa.',
    //                     'data' => null
    //                 ], 200);
    //         } else {
    //             return response()->json([
    //                 'status' => 422,
    //                 'message' => 'El id ingresado es incorrecto.',
    //                 'data' => 'UUID Formato incorrecto.'
    //             ], 422);
    //         }

    //     } catch (AuthorizationException $th) {
    //         return response()->json([
    //             'status' => $th->getCode(),
    //             'message' => 'No autorizado!.',
    //             'data' => $th->getMessage()
    //         ], 401);
    //     } catch (Exception $e) {
    //         return response()->json([
    //             'status' => $e->getCode(),
    //             'message' => 'Ocurrio un error!.',
    //             'data' => $e->getMessage()
    //         ], 400);
    //     }
    // }

    // public function eliminarComercio($id)
    // {
    //     try {
    //         $comercio = Comercio::find($id);
    //         if ($comercio != null) {
    //             $comercio->update([
    //                 'estado' => false
    //             ]);
    //             return response()->json([
    //                 'status' => 200,
    //                 'message' => 'Entidad Comercial eliminada correctamente.',
    //                 'data' => null
    //             ], 200);
    //         } else {
    //             return response()->json([
    //                 'status' => 200,
    //                 'message' => 'No se encontro la Entidad Comercial indicada.',
    //                 'data' => null
    //             ], 200);
    //         }
    //     } catch (AuthorizationException $th) {
    //         return response()->json([
    //             'status' => $th->getCode(),
    //             'message' => 'No autorizado!.',
    //             'data' => $th->getMessage()
    //         ], 401);
    //     } catch (Exception $e) {
    //         return response()->json([
    //             'status' => $e->getCode(),
    //             'message' => 'Ocurrio un error!.',
    //             'data' => $e->getMessage()
    //         ], 400);
    //     }
    // }

    // public function listarAllComercios()
    // {
    //     try {
    //         $lst_comercios = Comercio::all()->where('estado', true);
    //         if ($lst_comercios != null) {
    //             return response()->json([
    //                 'status' => 200,
    //                 'message' => 'Lista de entidades comerciales. ',
    //                 'data' => $lst_comercios
    //             ]);
    //         } else {
    //             return response()->json([
    //                 'status' => 200,
    //                 'message' => 'No existen entidades comerciales',
    //                 'data' => $lst_comercios
    //             ]);
    //         }
    //     } catch (\Throwable $th) {
    //         return response()->json([
    //             'status' => $th->getCode(),
    //             'message' => 'Ocurrio un error!. ',
    //             'data' => $th->getMessage()
    //         ], $th->getCode());
    //     }
    // }
}