<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EstilistaController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\PerfilController;
use App\Http\Controllers\Api\ServicioController;
use App\Http\Controllers\Api\HorarioController;
use App\Http\Controllers\Api\ReservaController;
use App\Http\Controllers\Api\PagoController;

Route::prefix('v1')->group(function () {
    // Rutas de autenticación públicas
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login',    [AuthController::class, 'login']);

    // Rutas protegidas con Sanctum
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('user',   [AuthController::class, 'user']);
        Route::post('logout',[AuthController::class, 'logout']);

        // Otros recursos de la API
        Route::apiResource('estilistas', EstilistaController::class);
        Route::apiResource('users', UserController::class);
        Route::apiResource('perfiles', PerfilController::class);
        Route::apiResource('servicios', ServicioController::class);
        Route::apiResource('horarios', HorarioController::class);
        Route::apiResource('reservas', ReservaController::class);
        Route::apiResource('pagos', PagoController::class);

        // Rutas adicionales para reservas
        Route::get('reservas/estilistas/{servicio_id}', [ReservaController::class, 'getEstilistas']);
        Route::get('reservas/horarios/{estilista_id}/{fecha}/{servicio_id}', [ReservaController::class, 'getHorarios']);
        Route::get('horarios-estilista/dias-disponibles/{estilista_id}', [ReservaController::class, 'getDiasDisponibles']);
        
        // Nuevas rutas para gestión de reservas
        Route::put('reservas/{reserva}/estado', [ReservaController::class, 'cambiarEstado']);
        Route::post('reservas/{reserva}/pago', [ReservaController::class, 'registrarPago']);

        // Rutas adicionales para estilistas
        Route::post('estilistas/{estilista}/servicios', [EstilistaController::class, 'asignarServicios']);
        Route::post('estilistas/{estilista}/horarios', [EstilistaController::class, 'asignarHorario']);
        Route::delete('estilistas/{estilista}/horarios/{horario}', [EstilistaController::class, 'eliminarHorario']);

        // Rutas adicionales para pagos
        Route::get('pagos/resumen', [PagoController::class, 'resumen']);
    });
});

