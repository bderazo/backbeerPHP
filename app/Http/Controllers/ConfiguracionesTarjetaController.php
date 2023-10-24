<?php

namespace App\Http\Controllers;

use App\Models\ConfiguracionesTarjeta;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ConfiguracionesTarjetaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function crearConfiguracionesTarjeta(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_tarjeta_id' => 'required|exists:user_tarjeta,id',
                'estado' => 'nullable',
                'text_label' => 'nullable',
                'flag_value' => 'nullable',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => 422,
                    'message' => 'Error al validar los datos de entrada.',
                    'data' => $validator->errors()
                ], 422);
            } else {
                $configuraciones = ConfiguracionesTarjeta::create([
                    'user_tarjeta_id' => $request->user_tarjeta_id,
                    'estado' => true,
                    'text_label' => $request->text_label,
                    'flag_value' => $request->flag_value,
                ]);
                return response()->json([
                    'status' => 201,
                    'message' => 'Configuraciones de Tarjeta creado correctamente.',
                    'data' => $configuraciones
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

    public function actualizarConfiguracionesTarjeta(Request $request, $id)
    {
        try {
            $configuraciones = ConfiguracionesTarjeta::where('id', $id)->first();
            if ($configuraciones != null) {
                $configuraciones->update($request->all());
                return response()->json([
                    'status' => 200,
                    'message' => 'Configuraciones de tarjeta actualizada correctamente.',
                    'data' => $configuraciones
                ], 200);
            } else {
                return response()->json([
                    'status' => 200,
                    'message' => 'No se encontro la configuracione indicada.',
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
}