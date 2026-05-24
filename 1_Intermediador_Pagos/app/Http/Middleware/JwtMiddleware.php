<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;
class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
   public function handle(Request $request, Closure $next)
    {
        // 1. Obtener el token del encabezado HTTP Authorization
        $header = $request->header('Authorization');
        
        if (!$header || !str_starts_with($header, 'Bearer ')) {
            return response()->json(['error' => 'Acceso denegado. Token no proporcionado o formato incorrecto.'], 401);
        }

        try {
            // 2. Extraer el string puro del Token JWT
            $token = explode(' ', $header)[1];
            
            // 3. Decodificar usando las variables definidas en el .env
            $decoded = JWT::decode($token, new Key(env('JWT_SECRET'), env('JWT_ALGORITHM', 'HS256')));
            
            // Adjuntamos los datos decodificados al request por si los necesitamos más adelante
            $request->attributes->add(['usuario_autenticado' => $decoded]);

            return $next($request);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Token inválido o expirado.',
                'mensaje_tecnico' => $e->getMessage()
            ], 401);
        }
    }
}
