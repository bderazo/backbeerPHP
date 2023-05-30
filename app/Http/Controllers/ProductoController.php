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
            $lst_comercios = Producto::with('comercio_id')->where('estado', true)->get();
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

    public function listarProductosIdComercio($id, Request $request)
    {
        try {
            $sucursales = Producto::with(['comercio_id', 'categoria_producto_id'])->where('comercio_id', $id)->paginate(10);

            // Haz algo con las sucursales obtenidas, como devolverlas como respuesta JSON
            // return response()->json($sucursales);

            if ($sucursales != null) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Lista de productos de la entidad comercial. ',
                    'data' => $sucursales
                ]);
            } else {
                return response()->json([
                    'status' => 200,
                    'message' => 'No existen productos en la entidad comercial',
                    'data' => $sucursales
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

    public function listarProductosIdComercioCategoria(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'negocio' => 'required|exists:comercio,id',
                'categoria_id' => 'required|exists:categoria_producto,id',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => 422,
                    'message' => 'Error al validar los datos de entrada.',
                    'data' => $validator->errors()
                ], 422);
            } else {
                $comercio_id = $request->input('negocio');
                $categoria_id = $request->input('categoria_id');
                $sucursales = Producto::with(['comercio_id', 'categoria_producto_id'])->where('comercio_id', $comercio_id)->where('categoria_producto_id', $categoria_id)->get();

                // Haz algo con las sucursales obtenidas, como devolverlas como respuesta JSON
                // return response()->json($sucursales);

                if ($sucursales != null) {
                    return response()->json([
                        'status' => 200,
                        'message' => 'Lista de productos de la entidad comercial por categoria. ',
                        'data' => $sucursales
                    ]);
                } else {
                    return response()->json([
                        'status' => 200,
                        'message' => 'No existen productos en la entidad comercial por categoria',
                        'data' => $sucursales
                    ]);
                }
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => $th->getCode(),
                'message' => 'Ocurrio un error!. ',
                'data' => $th->getMessage()
            ], $th->getCode());
        }
    }

    public function ingresarRegistros(Request $request)
    {
        $registros = json_decode($request->getContent(), true);

        foreach ($registros as $registro) {
            Producto::create($registro);
        }

        return "Registros ingresados exitosamente.";
    }
}
