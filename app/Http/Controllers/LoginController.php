<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Carbon\Carbon;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    // FUNCIONANDO OK
    public function login(Request $request)
    {
        try {
            $request->validate([
                'correo' => 'required|string|email',
                'password' => 'required|string',
            ]);
            $credentials = $request->only('correo', 'password');
            $token = JWTAuth::attempt($credentials);
            if (!$token) {
                return response()->json([
                    'status' => 401,
                    'message' => 'Datos incorrectos',
                    'data' => null
                ], Response::HTTP_UNAUTHORIZED);
            } else {
                $user = JWTAuth::user();
                $usuario = Usuario::find($user->id);
                return response()->json([
                    'status' => 200,
                    'authorisation' => [
                        'data' => $usuario,
                        'token' => $token,
                        'type' => 'bearer',
                        'expires_in' => (auth('api')->factory()->getTTL()) / 60 . ' horas'
                    ]
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => $th->getCode(),
                'message' => 'Error al intentar iniciar sesion.',
                'data' => $th->getMessage()

            ], 400);
        }
    }

    public function solicitarOlvidoClave(Request $request)
    {
        try {
            $input = $request->only('correo');
            $validator = Validator::make($input, [
                'correo' => 'required|email|exists:usuarios,correo',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 422,
                    'message' => 'Ha ocurrido un error al validar el Email!',
                    'data' => $validator->errors()
                ], 422);
            } else {
                $response = Password::sendResetLink($input);
                switch ($response) {
                    case Password::RESET_LINK_SENT:
                        return response()->json([
                            'status' => 200,
                            'message' => 'Le enviaremos un enlace a su correo para restablecer la contraseña.',
                            'data' => $response
                        ], 200);
                        break;
                    case Password::INVALID_USER:
                        return response()->json([
                            'status' => 400,
                            'message' => 'Email invalido.',
                            'data' => $response
                        ], 400);
                        break;
                    case Password::RESET_THROTTLED:
                        return response()->json([
                            'status' => 400,
                            'message' => 'Ya se realizo un peticion para restablecer clave, espere unos minutos.',
                            'data' => $response
                        ], 400);
                        break;
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

    protected function cambiarClave(Request $request)
    {

        $input = $request->only('correo', 'token', 'password', 'password_confirmation');
        $validator = Validator::make($input, [
            'token' => 'required',
            'correo' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);
        if ($validator->fails()) {
            return response([
                'status' => 422,
                'message' => 'Ocurrio un error al validar los datos.',
                'data' => $validator->errors()
            ], 422);
        } else {
            $response = Password::reset($input, function ($user, $password) {
                $user->forceFill(['password' => Hash::make($password)])->save();
                event(new PasswordReset($user));
            });
            switch ($response) {
                case Password::PASSWORD_RESET:
                    return response()->json([
                        'status' => 200,
                        'message' => 'Clave cambiada correctamente..',
                        'data' => $response
                    ], 200);
                    break;
                case Password::INVALID_USER:
                    return response()->json([
                        'status' => 400,
                        'message' => 'Email invalido.',
                        'data' => $response
                    ], 400);
                    break;
                case Password::INVALID_TOKEN:
                    return response()->json([
                        'status' => 400,
                        'message' => 'El token es invalido.',
                        'data' => $response
                    ], 400);
                    break;

            }
        }
    }

    public function getClientIPaddress(Request $request)
    {
        $clientIp = $request->ip();
        return $clientIp;
    }

}
