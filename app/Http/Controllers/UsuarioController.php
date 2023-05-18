<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UsuarioController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth:api');
    }
    public function crearUsuario(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nombres' => 'required',
                'apellidos' => 'required',
                'correo' => 'required|unique:usuarios,correo',
                'password' => 'required',
                'rol' => 'required',
                'registrado_por' => 'required'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => 422,
                    'message' => 'Error al validar los datos de entrada.',
                    'data' => $validator->errors()
                ], 422);
            } else {
                $usuario = Usuario::create([
                    'nombres' => $request->nombres,
                    'apellidos' => $request->apellidos,
                    'correo' => $request->correo,
                    'password' => Hash::make($request->password),
                    'rol' => $request->rol,
                    'identificacion' => $request->identificacion,
                    'registrado_por' => $request->registrado_por,
                ]);
                $input = $request->only('correo');
                return response()->json([
                    'status' => 201,
                    'message' => 'Usuario creado correctamente.',
                    // 'email' => Password::sendResetLink($input),
                    'data' => $usuario
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

    public function verUsuario($id)
    {
        try {
            if (Str::isUuid($id)) {
                $usuario = Usuario::get()->where('id', $id)->first();
                if ($usuario != null) {
                    return response()->json([
                        'status' => 200,
                        'message' => 'Datos de Usuario.',
                        'data' => $usuario
                    ]);
                } else {
                    return response()->json([
                        'status' => 200,
                        'message' => 'No se encontro datos del Usuario indicado.',
                        'data' => null
                    ]);
                }
            } else {
                $usuario = Usuario::get()->where('id', $id)->first();
                if ($usuario != null) {
                    return response()->json([
                        'status' => 200,
                        'message' => 'Datos de Usuario.',
                        'data' => $usuario
                    ]);
                } else {
                    return response()->json([
                        'status' => 200,
                        'message' => 'No se encontro datos del Usuario indicado.',
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

    public function listarUsuarios(Request $request)
    {
        try {
            $request->merge([
                'page' => $request->input('page', 0) + 1
            ]);
            $lst_usuarios = Usuario::get();
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
