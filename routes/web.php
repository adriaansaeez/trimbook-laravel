<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EstilistaController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\HorarioController;


// Ruta principal accesible para todos (sin autenticación)
Route::get('/', function () {
    return view('welcome');
});

// Grupo de rutas protegidas solo para ADMIN
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Dashboard principal (requiere autenticación)
    Route::prefix('dashboard')->group(function(){
        Route::get('/', function () {
            return view('dashboard');
        })->middleware(['verified'])->name('dashboard');
    
        // USUARIOS (CRUD)
        Route::get('/users', [UserController::class, 'index'])->name('users.index'); 
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create'); 
        Route::post('/users', [UserController::class, 'store'])->name('users.store'); 
        Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit'); 
        Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update'); 
        Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy'); 
    
        // Estilistas CRUD
        Route::get('/estilistas', [EstilistaController::class, 'index'])->name('estilistas.index');
        Route::get('/estilistas/create', [EstilistaController::class, 'create'])->name('estilistas.create');
        Route::post('/estilistas', [EstilistaController::class, 'store'])->name('estilistas.store');
        Route::get('/estilistas/{estilista}/edit', [EstilistaController::class, 'edit'])->name('estilistas.edit');
        Route::put('/estilistas/{estilista}', [EstilistaController::class, 'update'])->name('estilistas.update'); // <--- Aquí
        Route::delete('/estilistas/{estilista}', [EstilistaController::class, 'destroy'])->name('estilistas.destroy');

        Route::get('estilistas/{estilista}/horarios', [EstilistaController::class, 'editHorarios'])->name('estilistas.horarios.edit');
        Route::put('estilistas/{estilista}/horarios', [EstilistaController::class, 'updateHorarios'])->name('estilistas.horarios.update');

    
        // CRUD de Servicios
        Route::get('/servicios', [ServicioController::class, 'index'])->name('servicios.index');
        Route::get('/servicios/create', [ServicioController::class, 'create'])->name('servicios.create');
        Route::post('/servicios', [ServicioController::class, 'store'])->name('servicios.store');
        Route::get('/servicios/{servicio}/edit', [ServicioController::class, 'edit'])->name('servicios.edit');
        Route::put('/servicios/{servicio}', [ServicioController::class, 'update'])->name('servicios.update');
        Route::delete('/servicios/{servicio}', [ServicioController::class, 'destroy'])->name('servicios.destroy');

        // Horarios CRUD
        Route::get('/horarios', [HorarioController::class, 'index'])->name('horarios.index');
        Route::get('/horarios/create', [HorarioController::class, 'create'])->name('horarios.create');
        Route::post('/horarios', [HorarioController::class, 'store'])->name('horarios.store');
        Route::get('/horarios/{horario}/edit', [HorarioController::class, 'edit'])->name('horarios.edit');
        Route::put('/horarios/{horario}', [HorarioController::class, 'update'])->name('horarios.update');
        Route::delete('/horarios/{horario}', [HorarioController::class, 'destroy'])->name('horarios.destroy');

     
    });
    // Perfil de usuario (disponible para todos los usuarios autenticados)
    Route::get('/profile', [PerfilController::class, 'index'])->name('perfil.index');
    Route::get('/profile/edit', [PerfilController::class, 'edit'])->name('perfil.edit');
    Route::patch('/profile', [PerfilController::class, 'update'])->name('perfil.update');
    
});

// Grupo de rutas protegidas para ADMIN y ESTILISTA dentro de "dashboard"
Route::middleware(['auth', 'role:admin|estilista'])->prefix('dashboard')->group(function () {
    // Aquí puedes agregar rutas específicas para estilistas y administradores si las necesitas
});

// Grupo de rutas protegidas para TODOS (admin, estilista, cliente)
Route::middleware(['auth', 'role:admin|estilista|cliente'])->group(function () {
    // CRUD de Reservas
    Route::get('/reservas', [ReservaController::class, 'index'])->name('reservas.index'); // Listar reservas
    Route::get('/reservas/create', [ReservaController::class, 'create'])->name('reservas.create'); // Formulario para crear una reserva
    Route::post('/reservas', [ReservaController::class, 'store'])->name('reservas.store'); // Guardar una reserva
    Route::delete('/reservas/{reserva}', [ReservaController::class, 'destroy'])->name('reservas.destroy'); // 🛑 Nueva ruta

    // Rutas para carga de datos dinámicos vía AJAX con Axios
    Route::get('/reservas/estilistas/{servicio_id}', [ReservaController::class, 'getEstilistas'])->name('reservas.getEstilistas');
    Route::get('/reservas/horarios/{estilista_id}/{fecha}', [ReservaController::class, 'getHorarios'])->name('reservas.getHorarios');
});




require __DIR__.'/auth.php';
