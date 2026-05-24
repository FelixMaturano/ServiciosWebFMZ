<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\TransaccionController;
use App\Http\Middleware\JwtMiddleware;
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [LoginController::class, 'login']);

// Grupo de rutas protegidas mediante la directiva del ingeniero
Route::middleware([JwtMiddleware::class])->group(function () {
    
    Route::post('/transaccion', [TransaccionController::class, 'store']);
    
});