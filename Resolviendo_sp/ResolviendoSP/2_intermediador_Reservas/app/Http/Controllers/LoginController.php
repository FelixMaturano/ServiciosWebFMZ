<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Firebase\JWT\JWT;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);

            $user = User::where('email', $request->email)->first();
            
            if (!$user) {
                return response()->json([
                    'error' => 'Usuario no encontrado',
                    'email_buscado' => $request->email
                ], 401);
            }
            
            if (!Hash::check($request->password, $user->password)) {
                return response()->json([
                    'error' => 'Contraseña incorrecta'
                ], 401);
            }

            $payload = [
                'iss' => "intermediador_reservas_api",
                'iat' => time(),
                'exp' => time() + (60 * 60),
                'uid' => $user->id,
                'email' => $user->email
            ];

            $token = JWT::encode($payload, env('JWT_SECRET'), env('JWT_ALGORITHM', 'HS256'));

            return response()->json([
                'message' => 'Autenticación exitosa',
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email
                ]
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => 'Validación fallida',
                'messages' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al procesar login',
                'message' => $e->getMessage(),
                'debug' => env('APP_DEBUG') ? $e->getTraceAsString() : null
            ], 500);
        }
    }
}
