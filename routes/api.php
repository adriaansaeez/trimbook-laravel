<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EstilistaController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\PerfilController;
use App\Http\Controllers\Api\ServicioController;
use App\Http\Controllers\Api\HorarioController;
use App\Http\Controllers\Api\ReservaController;

Route::prefix('v1')->group(function () {
    // Autenticación
    Route::post('register', [AuthController::class,'register']);
    Route::post('login',    [AuthController::class,'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('user',   [AuthController::class,'user']);
        Route::post('logout',[AuthController::class,'logout']);

        // CRUD Resources
        Route::apiResource('users', UserController::class);
        Route::apiResource('estilistas', EstilistaController::class);
        Route::apiResource('perfiles', PerfilController::class);
        Route::apiResource('servicios', ServicioController::class);
        Route::apiResource('horarios', HorarioController::class);
        Route::apiResource('reservas', ReservaController::class);

        // Endpoints auxiliares para Reservas
        Route::get('reservas/estilistas/{servicio_id}', [ReservaController::class, 'getEstilistas']);
        Route::get('reservas/horarios/{estilista_id}/{fecha}/{servicio_id}', [ReservaController::class, 'getHorarios']);
    });
});
