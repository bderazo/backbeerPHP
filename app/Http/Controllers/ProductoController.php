<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function crearProducto(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'comercio_id' => 'required|exists:comercio,id',
                'nombre' => 'required',
                'descripcion' => 'required',
                'tipo_producto' => 'required',
                'categoria_producto_id' => 'required|exists:categoria_producto,id',
                'estado' => 'nullable',
                'codigo_barras' => 'required',
                'tipo_impuesto' => 'required',
                'registrado_por' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => 422,
                    'message' => 'Error al validar los datos de entrada.',
                    'data' => $validator->errors()
                ], 422);
            } else {
                $producto = Producto::create([
                    'comercio_id' => $request->comercio_id,
                    'nombre' => $request->nombre,
                    'descripcion' => $request->descripcion,
                    'tipo_producto' => $request->tipo_producto,
                    'categoria_producto_id' => $request->categoria_producto_id,
                    'estado' => true,
                    'codigo_barras' => $request->codigo_barras,
                    'tipo_impuesto' => $request->tipo_impuesto,
                    'registrado_por' => $request->registrado_por,
                ]);
                return response()->json([
                    'status' => 201,
                    'message' => 'Producto creado correctamente.',
                    'data' => $producto
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

    public function listarAllProductos()
    {
        try {
            $lst_comercios = Producto::all()->where('estado', true);
            if ($lst_comercios != null) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Lista de productos por entidad comercial. ',
                    'data' => $lst_comercios
                ]);
            } else {
                return response()->json([
                    'status' => 200,
                    'message' => 'No existen productos por entidad comercial',
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