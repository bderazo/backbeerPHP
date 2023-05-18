<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

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
                $usuario = Usuario::with('userTarjeta')->find($user->id);
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
}
