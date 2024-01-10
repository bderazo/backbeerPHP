<?php

namespace App\Http\Controllers;

use App\Models\CategoriaProducto;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CategoriaProductoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function crearCategoria(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nombre' => 'required|unique:categoria_producto',
                'estado' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => 422,
                    'message' => 'Error al validar los datos de entrada.',
                    'data' => $validator->errors()
                ], 422);
            } else {
                $categoria = CategoriaProducto::create([
                    'nombre' => $request->nombre,
                    'estado' => $request->estado
                ]);
                return response()->json([
                    'status' => 201,
                    'message' => 'Categoria creada con exito.',
                    'data' => $categoria
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

    public function verCategoria($id)
    {
        try {
            if (Str::isUuid($id)) {
                $categoria = CategoriaProducto::get()->where('id', $id)->first();
                if ($categoria != null) {
                    return response()->json([
                        'status' => 200,
                        'message' => 'Datos de la categoria.',
                        'data' => $categoria
                    ]);
                } else {
                    return response()->json([
                        'status' => 200,
                        'message' => 'No se encontro datos de la categoria indicada.',
                        'data' => null
                    ]);
                }
            } else {
                $categoria = CategoriaProducto::get()->where('id', $id)->first();
                if ($categoria != null) {
                    return response()->json([
                        'status' => 200,
                        'message' => 'Datos de la categoria.',
                        'data' => $categoria
                    ]);
                } else {
                    return response()->json([
                        'status' => 200,
                        'message' => 'No se encontro datos de la categoria indicada.',
                        'data' => null
                    ]);
                }
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => $th->getCode(),
                'message' => 'Ocurrio un error!.',
                'data' => $th->getMessage()
            ], 400);
        }
    }

    public function listarAllCategorias(){
        try {
            $lst_categorias = CategoriaProducto::all()->where('estado', true);
            if ($lst_categorias != null) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Lista de Categorias para producto.',
                    'data' => $lst_categorias
                ]);
            } else {
                return response()->json([
                    'status' => 200,
                    'message' => 'No existen Categorias para producto',
                    'data' => $lst_categorias
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