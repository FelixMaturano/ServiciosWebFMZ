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
        // Validar campos de entrada
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Buscar al usuario por correo
        $user = User::where('email', $request->email)->first();

        // Verificar existencia y contraseña cifrada
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Credenciales de comercio incorrectas.'], 401);
        }

        // Definir la estructura (Payload) del JWT
        $payload = [
            'iss' => "intermediador_pagos_api", // Emisor
            'iat' => time(),                     // Fecha de creación
            'exp' => time() + (60 * 60),         // Expiración (1 hora)
            'uid' => $user->id,
            'email' => $user->email
        ];

        // Codificar Token
        $token = JWT::encode($payload, env('JWT_SECRET'), env('JWT_ALGORITHM'));

        return response()->json([
            'mensaje' => 'Autenticación exitosa',
            'token' => $token
        ], 200);
    }
}
