<?php

namespace App\Http\Controllers;

use App\Models\CategoriaProducto;
use App\Models\TipoUsuario;
use App\Models\Usuario;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CategoriaProductoController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth:api');
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

// public function actualizarUsuario(Request $request, $id)
// {
//     try {
//         $this->authorize('actualizar-usuario');
//         $usuario = Usuario::find($id);
//         if ($usuario != null) {
//             $usuario->update($request->all());
//             $usuario->contacto->update($request->all());
//             return response()->json([
//                 'status' => 200,
//                 'message' => 'Usuario actualizado correctamente.',
//                 'data' => $usuario
//             ]);
//         } else {
//             return response()->json([
//                 'status' => 200,
//                 'message' => 'No se encontro al Usuario indicado.',
//                 'data' => null
//             ]);
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

// public function eliminarUsuario($id)
// {
//     try {
//         $usuario = Usuario::find($id);
//         if ($usuario != null) {
//             $usuario->update([
//                 'estado' => false
//             ]);
//             return response()->json([
//                 'status' => 200,
//                 'message' => 'Usuario eliminado correctamente.',
//                 'data' => null
//             ]);
//         } else {
//             return response()->json([
//                 'status' => 200,
//                 'message' => 'No se encontro el Usuario indicado.',
//                 'data' => null
//             ]);
//         }

//     } catch (\Throwable $th) {
//         return response()->json([
//             'status' => $th->getCode(),
//             'message' => 'Ocurrio un error!.',
//             'data' => $th->getMessage()
//         ], 400);
//     }
// }

// public function crearUsuarioEntidad(Request $request)
// {
//     try {
//         $this->authorize('registrar-nuevoUsuario-entidad');
//         $validator = Validator::make($request->all(), [
//             'nombre' => 'required',
//             'apellido' => 'required',
//             'correo' => 'required|unique:usuarios,correo',
//             'correo_recuperacion' => 'required',
//             'identificacion' => 'required|unique:usuarios,identificacion',
//             'tipo_identificacion' => 'required',
//             'genero' => 'required',
//             // 'usuario_registra'=>'required|exists:usuarios,id',
//             'tipo_usuario' => 'required|exists:tipos_usuarios,id',
//             'cod_pais' => 'required|exists:cod_paises,id',
//             // 'entidad_id'=>'required|exists:entidades_comerciales,id',

//             'celular' => 'required',
//             'fijo' => 'nullable',
//             'whatsapp' => 'nullable'
//         ]);
//         if ($validator->fails()) {
//             return response()->json([
//                 'status' => 422,
//                 'message' => 'Error al validar los datos de entrada.',
//                 'data' => $validator->errors()
//             ], 422);
//         } else {
//             $roles = TipoUsuario::get();
//             if (removeElementsFromJson($roles, 'ADMINISTRADOR', $request->tipo_usuario)) {
//                 $usuario = Usuario::create([
//                     'nombre' => $request->nombre,
//                     'apellido' => $request->apellido,
//                     'correo' => $request->correo,
//                     'correo_recuperacion' => $request->correo_recuperacion,
//                     'identificacion' => $request->identificacion,
//                     'tipo_identificacion' => $request->tipo_identificacion,
//                     'genero' => $request->genero,
//                     'usuario_registra' => auth()->user()->id,
//                     'tipo_usuario' => $request->tipo_usuario,
//                     'cod_pais' => $request->cod_pais,
//                     'entidad_id' => auth()->user()->entidad_id,
//                     'password' => Hash::make('adndigital2022')
//                 ]);
//                 $contacto = Contacto::create([
//                     'usuario_id' => $usuario->id,
//                     'celular' => $request->celular,
//                     'fijo' => $request->fijo,
//                     'whatsapp' => $request->whatsapp,
//                 ]);
//                 $usuario_clave = UsuarioClave::create([
//                     'clave' => $usuario->password,
//                     'usuario_id' => $usuario->id,
//                     'estado' => true,
//                     'fecha' => Carbon::now()->isoFormat('Y-M-DD')
//                 ]);
//                 $input = $request->only('correo');
//                 return response()->json([
//                     'status' => 201,
//                     'message' => 'Usuario creado correctamente.',
//                     'email' => Password::sendResetLink($input),
//                     'data' => $usuario
//                 ], 201);
//             } else {
//                 return response()->json([
//                     'status' => 422,
//                     'message' => 'Error al validar los datos de entrada.',
//                     'data' => 'El tipo de usuario no es valido.'
//                 ], 422);
//             }


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

// public function actualizarUsuarioEntidad(Request $request, $id)
// {
//     try {
//         $this->authorize('actualizar-usuario-entidad');
//         $usuario = Usuario::where('id', $id)->where('entidad_id', auth()->user()->entidad_id)->first();
//         if ($usuario != null) {
//             $usuario->update($request->all());
//             $usuario->contacto->update($request->all());
//             return response()->json([
//                 'status' => 200,
//                 'message' => 'Usuario actualizado correctamente.',
//                 'data' => $usuario
//             ]);
//         } else {
//             return response()->json([
//                 'status' => 200,
//                 'message' => 'No se encontro al Usuario indicado.',
//                 'data' => null
//             ]);
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

// public function cambiarPassword(Request $request)
// {
//     try {
//         $validator = Validator::make($request->all(), [
//             'password' => 'min:8'
//         ]);
//         if ($validator->fails()) {
//             return response()->json([
//                 'status' => 200,
//                 'message' => 'Error al validar la contraseña',
//                 'data' => $validator->errors()
//             ]);
//         } else {
//             $usuario = Usuario::with('claves')->where('id', auth()->id())->first();
//             foreach ($usuario->claves as $key => $value) {
//                 if (Hash::check($request->password, $value->clave)) {
//                     return response()->json([
//                         'status' => 422,
//                         'message' => 'No puedes volver a utilizar una contrasena anterior.',
//                         'data' => null
//                     ], 422);
//                     break;
//                 }

//             }
//             $usuario->update(['password' => Hash::make($request->password)]);
//             $usuario_clave = UsuarioClave::create([
//                 'clave' => $usuario->password,
//                 'usuario_id' => $usuario->id,
//                 'estado' => true,
//                 'fecha' => Carbon::now()->isoFormat('Y-M-DD')
//             ]);
//             return response()->json([
//                 'status' => 200,
//                 'message' => 'Password actualizada correctamente.',
//                 'data' => $usuario
//             ], 200);
//         }
//     } catch (\Throwable $th) {
//         return response()->json([
//             'status' => $th->getCode(),
//             'message' => 'Ocurrio un error!.',
//             'data' => $th->getMessage()
//         ], 400);
//     }

// }

// public function actualizarPerfil(Request $request)
// {
//     try {
//         $usuario = Usuario::where('id', auth()->id())->first();
//         $usuario->update($request->all());
//         $usuario->contacto->update($request->all());
//         return response()->json([
//             'status' => 200,
//             'message' => 'Perfil actualizado correctamente.',
//             'data' => $usuario
//         ]);
//     } catch (\Throwable $th) {
//         return response()->json([
//             'status' => $th->getCode(),
//             'message' => 'Ocurrio un error!.',
//             'data' => $th->getMessage()
//         ], 400);
//     }
// }

// public function buscarUsuarioCedula(Request $request)
// {
//     try {
//         $this->authorize('buscar-usuario');
//         $usuario = Usuario::where('identificacion', $request->identificacion)->first();
//         if ($usuario != null) {
//             return response()->json([
//                 'status' => 200,
//                 'message' => 'Usuario encontrado.',
//                 'data' => $usuario
//             ]);
//         } else {
//             return response()->json([
//                 'status' => 200,
//                 'message' => 'No se encontro al Usuario indicado.',
//                 'data' => null
//             ]);
//         }
//     } catch (AuthorizationException $ae) {
//         return response()->json([
//             'status' => 401,
//             'message' => 'No tienes permisos!',
//             'data' => $ae->getMessage()
//         ], 401);
//     } catch (\Throwable $th) {
//         return response()->json([
//             'status' => $th->getCode(),
//             'message' => 'Ocurrio un error!.',
//             'data' => $th->getMessage()
//         ], 400);
//     }
// }

// public function buscarUsuarioCedulaEntidad(Request $request)
// {
//     try {
//         $this->authorize('buscar-usuario-entidad');
//         $usuario = Usuario::where('identificacion', $request->identificacion)->where('entidad_id', auth()->user()->entidad_id)->first();
//         if ($usuario != null) {
//             return response()->json([
//                 'status' => 200,
//                 'message' => 'Usuario encontrado.',
//                 'data' => $usuario
//             ]);
//         } else {
//             return response()->json([
//                 'status' => 200,
//                 'message' => 'No se encontro al Usuario indicado.',
//                 'data' => null
//             ]);
//         }
//     } catch (AuthorizationException $ae) {
//         return response()->json([
//             'status' => 401,
//             'message' => 'No tienes permisos!',
//             'data' => $ae->getMessage()
//         ], 401);
//     } catch (\Throwable $th) {
//         return response()->json([
//             'status' => $th->getCode(),
//             'message' => 'Ocurrio un error!.',
//             'data' => $th->getMessage()
//         ], 400);
//     }
// }
}