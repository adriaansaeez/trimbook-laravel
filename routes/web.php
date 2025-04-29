<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EstilistaController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\HorarioController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PagoController;


// Ruta principal accesible para todos (sin autenticación)
Route::get('/', function () {
    return view('welcome');
});

// Ruta de inicio para usuarios autenticados
Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/calendario-data', [HomeController::class, 'getCalendarioData'])->name('calendario.data');
    
    // Rutas de Pagos
    Route::get('/pagos/home', [PagoController::class, 'home'])->name('pagos.home');
    Route::get('/pagos', [PagoController::class, 'index'])->name('pagos.index');
    Route::get('/pagos/{pago}', [PagoController::class, 'show'])->name('pagos.show');
    Route::get('/pagos/{pago}/detalles', [PagoController::class, 'getDetalles'])->name('pagos.detalles');
    Route::get('/pagos/{pago}/pdf', [PagoController::class, 'generarPDF'])->name('pagos.pdf');
    Route::get('/pagos/{pago}/edit', [PagoController::class, 'edit'])->name('pagos.edit');
    Route::post('/pagos', [PagoController::class, 'store'])->name('pagos.store');
    Route::put('/pagos/{pago}', [PagoController::class, 'update'])->name('pagos.update');
    Route::delete('/pagos/{pago}', [PagoController::class, 'destroy'])->name('pagos.destroy');
    Route::get('/pagos/export/excel', [PagoController::class, 'exportarExcel'])->name('pagos.export.excel');
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
        Route::put('estilistas/{estilista}/horarios', [EstilistaController::class, 'asignarHorario'])->name('estilistas.horarios.update');

        // Mostrar formulario de asignación
        Route::get('/estilistas/asignar-servicios', [EstilistaController::class, 'formAsignarServicios'])->name('estilistas.asignar.form');
        // Procesar el formulario
        Route::post('/estilistas/asignar-servicios', [EstilistaController::class, 'asignarServicios'])->name('estilistas.asignar.servicios');


    
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
        Route::get('/horarios/{horario}', [HorarioController::class, 'show'])->name('horarios.show');
        Route::get('/horarios/{horario}/edit', [HorarioController::class, 'edit'])->name('horarios.edit');
        Route::put('/horarios/{horario}', [HorarioController::class, 'update'])->name('horarios.update');
        Route::delete('/horarios/{horario}', [HorarioController::class, 'destroy'])->name('horarios.destroy');
        // Asignaciones de horarios a estilistas
        Route::get('/asignar-horario', [EstilistaController::class, 'vistaAsignarHorarioIndex'])->name('asignar_horario.index');
        Route::get('/asignar-horario/create/{estilista}', [EstilistaController::class, 'vistaAsignarHorarioForm'])->name('asignar_horario.create');
        Route::post('/asignar-horario/store/{estilista}', [EstilistaController::class, 'guardarAsignacionHorario'])->name('asignar_horario.store');
        // Asegúrate de que la ruta espera un parámetro llamado 'id'
        Route::delete('/dashboard/horarios-estilista/{id}', [EstilistaController::class, 'eliminarHorarioPivote'])->name('horarios_estilista.delete');

        // PAGOS (CRUD)
        Route::get('/pagos', [PagoController::class, 'index'])->name('pagos.index');
        Route::get('/pagos/{pago}', [PagoController::class, 'show'])->name('pagos.show');
        Route::get('/pagos/exportar-excel', [PagoController::class, 'exportarExcel'])->name('pagos.exportar-excel');

     
    });
   
    
});

// Grupo de rutas protegidas para ADMIN y ESTILISTA dentro de "dashboard"
Route::middleware(['auth', 'role:admin|estilista'])->prefix('dashboard')->group(function () {
    // Aquí puedes agregar rutas específicas para estilistas y administradores si las necesitas
    Route::put('/reservas/{reserva}/estado', [ReservaController::class, 'cambiarEstado'])->name('reservas.cambiarEstado');

});

// Grupo de rutas protegidas para TODOS (admin, estilista, cliente)
Route::middleware(['auth', 'role:admin|estilista|cliente'])->group(function () {
    // CRUD de Reservas
    Route::get('/reservas', [ReservaController::class, 'index'])->name('reservas.index'); // Listar reservas
    Route::get('/reservas/create', [ReservaController::class, 'create'])->name('reservas.create'); // Formulario de creación
    Route::post('/reservas', [ReservaController::class, 'store'])->name('reservas.store'); // Guardar reserva
    Route::get('/reservas/{reserva}', [ReservaController::class, 'show'])->name('reservas.show'); // Ver detalles
    Route::get('/reservas/{reserva}/edit', [ReservaController::class, 'edit'])->name('reservas.edit'); // Formulario de edición
    Route::put('/reservas/{reserva}', [ReservaController::class, 'update'])->name('reservas.update'); // Actualizar reserva
    Route::delete('/reservas/{reserva}', [ReservaController::class, 'destroy'])->name('reservas.destroy'); // Eliminar reserva
    
    // Rutas para gestionar estados de reservas
    Route::post('/reservas/{reserva}/cancelar', [ReservaController::class, 'cancelar'])->name('reservas.cancelar');
    Route::post('/reservas/{reserva}/confirmar', [ReservaController::class, 'confirmar'])->name('reservas.confirmar');
    Route::post('/reservas/{reserva}/completar', [ReservaController::class, 'completar'])->name('reservas.completar');
    
    // Rutas para gestionar pagos
    Route::post('/reservas/{reserva}/pago', [ReservaController::class, 'registrarPago'])->name('reservas.pago');
    
    // Rutas para exportar reservas
    Route::get('/reservas/{reserva}/exportar-pdf', [ReservaController::class, 'exportarPDF'])->name('reservas.exportar-pdf');
    Route::get('/reservas/exportar-excel', [ReservaController::class, 'exportarExcel'])->name('reservas.exportar-excel');
    
    // Ruta para obtener estilistas por servicio
    Route::get('/reservas/estilistas/{servicio}', [ReservaController::class, 'getEstilistas'])->name('reservas.estilistas');

     // Perfil de usuario (disponible para todos los usuarios autenticados)
     Route::get('/profile', [PerfilController::class, 'index'])->name('perfil.index');
     Route::get('/profile/edit', [PerfilController::class, 'edit'])->name('perfil.edit');
     Route::patch('/profile', [PerfilController::class, 'update'])->name('perfil.update');
});

require __DIR__.'/auth.php';
