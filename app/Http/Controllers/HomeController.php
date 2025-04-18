<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Reserva;
use App\Models\Estilista;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    /**
     * Muestra la página de inicio para usuarios autenticados.
     */
    public function index(Request $request): View
    {
        // Obtener la fecha para el calendario semanal (por defecto la semana actual)
        $fechaBase = $request->has('semana') 
            ? Carbon::parse($request->semana) 
            : Carbon::now();
            
        // Calcular el inicio de la semana (lunes)
        // Si la fecha proporcionada es un lunes, usarla directamente
        if ($fechaBase->dayOfWeek == Carbon::MONDAY) {
            $inicioSemana = $fechaBase->copy();
        } else {
            // Si no es lunes, calcular el lunes de la semana actual
            $inicioSemana = $fechaBase->copy()->startOfWeek(Carbon::MONDAY);
        }
        
        $finSemana = $inicioSemana->copy()->endOfWeek();
        
        // Obtener el ID del estilista del usuario logueado
        $estilistaId = null;
        $estilista = Estilista::where('user_id', auth()->id())->first();
        if ($estilista) {
            $estilistaId = $estilista->id;
        }
        
        // Obtener las reservas para el calendario semanal según el rol del usuario
        $esEstilista = Auth::user()->hasRole('estilista');
        $esCliente = Auth::user()->hasRole('cliente');
        $esAdmin = Auth::user()->hasRole('admin');
        
        // Consulta base para las reservas con eager loading de relaciones
        $query = Reserva::with([
                'servicio', 
                'user.perfil', 
                'estilista.user.perfil'
            ])
            ->whereBetween('fecha', [$inicioSemana->format('Y-m-d'), $finSemana->format('Y-m-d')]);
        
        // Filtrar según el rol
        if ($esEstilista && $estilistaId) {
            // Si es estilista, mostrar sus propias reservas
            $query->where('estilista_id', $estilistaId);
        } else if ($esCliente) {
            // Si es cliente, mostrar sus propias reservas
            $query->where('user_id', auth()->id());
        }
        // Si es admin, mostrar todas las reservas (no se aplica filtro adicional)
        
        // Obtener las reservas
        $reservasSemana = $query->get();
        
        // Registrar para depuración
        Log::info('Reservas encontradas: ' . $reservasSemana->count());
        Log::info('Rango de fechas: ' . $inicioSemana->format('Y-m-d') . ' a ' . $finSemana->format('Y-m-d'));
        Log::info('Rol del usuario: ' . ($esEstilista ? 'estilista' : ($esCliente ? 'cliente' : ($esAdmin ? 'admin' : 'otro'))));
        
        // Definir las horas disponibles para cada día
        $horasDisponibles = [
            'lunes' => ['09:00', '09:30', '10:00', '10:30', '11:00', '11:30', '12:00', '12:30', '13:00', '13:30', '14:00', '14:30', '15:00', '15:30', '16:00', '16:30', '17:00', '17:30', '18:00', '18:30', '19:00', '19:30', '20:00'],
            'martes' => ['09:00', '09:30', '10:00', '10:30', '11:00', '11:30', '12:00', '12:30', '13:00', '13:30', '14:00', '14:30', '15:00', '15:30', '16:00', '16:30', '17:00', '17:30', '18:00', '18:30', '19:00', '19:30', '20:00'],
            'miércoles' => ['09:00', '09:30', '10:00', '10:30', '11:00', '11:30', '12:00', '12:30', '13:00', '13:30', '14:00', '14:30', '15:00', '15:30', '16:00', '16:30', '17:00', '17:30', '18:00', '18:30', '19:00', '19:30', '20:00'],
            'jueves' => ['09:00', '09:30', '10:00', '10:30', '11:00', '11:30', '12:00', '12:30', '13:00', '13:30', '14:00', '14:30', '15:00', '15:30', '16:00', '16:30', '17:00', '17:30', '18:00', '18:30', '19:00', '19:30', '20:00'],
            'viernes' => ['09:00', '09:30', '10:00', '10:30', '11:00', '11:30', '12:00', '12:30', '13:00', '13:30', '14:00', '14:30', '15:00', '15:30', '16:00', '16:30', '17:00', '17:30', '18:00', '18:30', '19:00', '19:30', '20:00'],
            'sábado' => ['09:00', '09:30', '10:00', '10:30', '11:00', '11:30', '12:00', '12:30', '13:00', '13:30', '14:00', '14:30', '15:00', '15:30', '16:00', '16:30', '17:00', '17:30', '18:00', '18:30', '19:00', '19:30', '20:00'],
            'domingo' => []
        ];
        
        return view('home', compact('inicioSemana', 'reservasSemana', 'horasDisponibles', 'estilistaId', 'esEstilista', 'esCliente', 'esAdmin'));
    }
    
    /**
     * Obtiene los datos del calendario para una semana específica.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCalendarioData(Request $request)
    {
        // Obtener la fecha para el calendario semanal
        $fechaBase = $request->has('fecha') 
            ? Carbon::parse($request->fecha) 
            : Carbon::now();
            
        // Obtener el desplazamiento de semanas (si existe)
        $desplazamiento = $request->has('desplazamiento') 
            ? (int)$request->desplazamiento 
            : 0;
            
        // Calcular el inicio de la semana (lunes)
        $inicioSemana = $fechaBase->copy()->startOfWeek(Carbon::MONDAY);
        
        // Aplicar el desplazamiento de semanas
        if ($desplazamiento != 0) {
            $inicioSemana->addWeeks($desplazamiento);
        }
        
        $finSemana = $inicioSemana->copy()->endOfWeek();
        
        // Obtener el ID del estilista del usuario logueado
        $estilistaId = null;
        $estilista = Estilista::where('user_id', auth()->id())->first();
        if ($estilista) {
            $estilistaId = $estilista->id;
        }
        
        // Obtener las reservas para el calendario semanal según el rol del usuario
        $esEstilista = Auth::user()->hasRole('estilista');
        $esCliente = Auth::user()->hasRole('cliente');
        $esAdmin = Auth::user()->hasRole('admin');
        
        // Consulta base para las reservas con eager loading de relaciones
        $query = Reserva::with([
                'servicio', 
                'user.perfil', 
                'estilista.user.perfil'
            ])
            ->whereBetween('fecha', [$inicioSemana->format('Y-m-d'), $finSemana->format('Y-m-d')]);
        
        // Filtrar según el rol
        if ($esEstilista && $estilistaId) {
            $query->where('estilista_id', $estilistaId);
        } else if ($esCliente) {
            $query->where('user_id', auth()->id());
        }
        
        // Obtener las reservas
        $reservas = $query->get();
        
        // Definir las horas disponibles para cada día
        $horasDisponibles = [
            'lunes' => ['09:00', '09:30', '10:00', '10:30', '11:00', '11:30', '12:00', '12:30', '13:00', '13:30', '14:00', '14:30', '15:00', '15:30', '16:00', '16:30', '17:00', '17:30', '18:00', '18:30', '19:00', '19:30', '20:00'],
            'martes' => ['09:00', '09:30', '10:00', '10:30', '11:00', '11:30', '12:00', '12:30', '13:00', '13:30', '14:00', '14:30', '15:00', '15:30', '16:00', '16:30', '17:00', '17:30', '18:00', '18:30', '19:00', '19:30', '20:00'],
            'miércoles' => ['09:00', '09:30', '10:00', '10:30', '11:00', '11:30', '12:00', '12:30', '13:00', '13:30', '14:00', '14:30', '15:00', '15:30', '16:00', '16:30', '17:00', '17:30', '18:00', '18:30', '19:00', '19:30', '20:00'],
            'jueves' => ['09:00', '09:30', '10:00', '10:30', '11:00', '11:30', '12:00', '12:30', '13:00', '13:30', '14:00', '14:30', '15:00', '15:30', '16:00', '16:30', '17:00', '17:30', '18:00', '18:30', '19:00', '19:30', '20:00'],
            'viernes' => ['09:00', '09:30', '10:00', '10:30', '11:00', '11:30', '12:00', '12:30', '13:00', '13:30', '14:00', '14:30', '15:00', '15:30', '16:00', '16:30', '17:00', '17:30', '18:00', '18:30', '19:00', '19:30', '20:00'],
            'sábado' => ['09:00', '09:30', '10:00', '10:30', '11:00', '11:30', '12:00', '12:30', '13:00', '13:30', '14:00', '14:30', '15:00', '15:30', '16:00', '16:30', '17:00', '17:30', '18:00', '18:30', '19:00', '19:30', '20:00'],
            'domingo' => []
        ];
        
        // Preparar los datos del calendario
        $calendario = [];
        foreach ($horasDisponibles['lunes'] as $hora) {
            $horaData = [
                'hora' => $hora,
                'reservas' => []
            ];
            
            foreach (['lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado', 'domingo'] as $index => $dia) {
                $fechaActual = $inicioSemana->copy()->addDays($index);
                $reserva = $reservas->first(function($r) use ($fechaActual, $hora) {
                    $fechaReserva = Carbon::parse($r->fecha);
                    $horaReserva = Carbon::parse($r->hora)->format('H:i');
                    return $fechaReserva->isSameDay($fechaActual) && $horaReserva === $hora;
                });
                
                if ($reserva) {
                    // Obtener el nombre del cliente desde el perfil
                    $nombreCliente = 'Cliente';
                    if ($reserva->user && $reserva->user->perfil) {
                        $nombreCliente = $reserva->user->perfil->nombre . ' ' . $reserva->user->perfil->apellidos;
                    } else if ($reserva->user) {
                        $nombreCliente = $reserva->user->username;
                    }
                    
                    // Obtener el nombre del estilista desde el perfil
                    $nombreEstilista = 'Estilista';
                    if ($reserva->estilista && $reserva->estilista->user && $reserva->estilista->user->perfil) {
                        $nombreEstilista = $reserva->estilista->user->perfil->nombre . ' ' . $reserva->estilista->user->perfil->apellidos;
                    } else if ($reserva->estilista && $reserva->estilista->user) {
                        $nombreEstilista = $reserva->estilista->user->username;
                    } else if ($reserva->estilista) {
                        $nombreEstilista = $reserva->estilista->nombre ?? 'Estilista';
                    }
                    
                    $horaData['reservas'][$dia] = [
                        'id' => $reserva->id,
                        'cliente' => $nombreCliente,
                        'estilista' => $nombreEstilista,
                        'servicio' => $reserva->servicio->nombre ?? 'Servicio',
                        'estado' => $reserva->estado
                    ];
                }
            }
            
            $calendario[] = $horaData;
        }
        
        return response()->json([
            'inicioSemana' => $inicioSemana->format('Y-m-d'),
            'calendario' => $calendario,
            'esEstilista' => $esEstilista,
            'esCliente' => $esCliente,
            'esAdmin' => $esAdmin
        ]);
    }
} 