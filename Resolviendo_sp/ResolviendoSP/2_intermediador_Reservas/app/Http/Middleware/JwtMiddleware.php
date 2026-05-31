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
    public function handle(Request $request, Closure $next): Response
    {
        $header = $request->header('Authorization');
        if(!$header || !str_starts_with($header, 'Bearer ')) {
            return response()->json(['error' => 'Token no proporcionado'], 401);
        }
        try{
            $token = explode(' ', $header)[1];
            $decoded = JWT::decode($token, new Key(env('JWT_SECRET'),env('JWT_ALGORITHM','HS256')));
            $request->attributes->add(['usuario_autenticado'=>$decoded]);
            
            return $next($request);
            
        }catch(Exception $e){
            return response()->json(['error' => 'Token inválido: '.$e->getMessage()], 401);
        }
        
    }
}
