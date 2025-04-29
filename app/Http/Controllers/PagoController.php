<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Reserva;
use App\Models\Estilista;
use App\Models\Servicio;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PagoController extends Controller
{
    /**
     * Muestra la lista de pagos con filtros opcionales
     */
    public function index(Request $request)
    {
        $query = Pago::with(['reserva.servicio', 'reserva.user', 'estilista']);

        // Filtros
        if ($request->has('fecha_desde') && $request->fecha_desde != '') {
            $query->whereDate('fecha_pago', '>=', $request->fecha_desde);
        }
        
        if ($request->has('fecha_hasta') && $request->fecha_hasta != '') {
            $query->whereDate('fecha_pago', '<=', $request->fecha_hasta);
        }
        
        if ($request->has('importe_min') && $request->importe_min != '') {
            $query->where('importe', '>=', $request->importe_min);
        }
        
        if ($request->has('importe_max') && $request->importe_max != '') {
            $query->where('importe', '<=', $request->importe_max);
        }
        
        if ($request->has('metodo_pago') && $request->metodo_pago != '') {
            $query->where('metodo_pago', $request->metodo_pago);
        }
        
        if ($request->has('servicio_id') && $request->servicio_id != '') {
            $query->whereHas('reserva', function($q) use ($request) {
                $q->where('servicio_id', $request->servicio_id);
            });
        }
        
        if ($request->has('estilista_id') && $request->estilista_id != '') {
            $query->where('estilista_id', $request->estilista_id);
        }
        
        if ($request->has('cliente_id') && $request->cliente_id != '') {
            $query->whereHas('reserva', function($q) use ($request) {
                $q->where('user_id', $request->cliente_id);
            });
        }

        // Ordenación
        $ordenarPor = $request->input('ordenar_por', 'fecha_pago');
        $orden = $request->input('orden', 'desc');
        $query->orderBy($ordenarPor, $orden);

        // Paginación
        $pagos = $query->paginate(10);

        // Datos para los filtros
        $servicios = Servicio::all();
        $estilistas = Estilista::all();
        $clientes = User::role('cliente')->get();
        $metodosPago = ['EFECTIVO', 'TARJETA', 'BIZUM', 'TRANSFERENCIA'];

        // Estadísticas
        $totalPagos = $pagos->sum('importe');
        $promedioPago = $pagos->avg('importe');
        $pagosPorMetodo = Pago::select('metodo_pago', DB::raw('count(*) as total'), DB::raw('sum(importe) as importe_total'))
            ->groupBy('metodo_pago')
            ->get();

        return view('pagos.index', compact(
            'pagos', 
            'servicios', 
            'estilistas', 
            'clientes', 
            'metodosPago',
            'totalPagos',
            'promedioPago',
            'pagosPorMetodo'
        ));
    }

    /**
     * Muestra los detalles de un pago específico
     */
    public function show(Pago $pago)
    {
        $pago->load(['reserva.servicio', 'reserva.user', 'estilista']);
        return view('pagos.show', compact('pago'));
    }

    /**
     * Almacena un nuevo pago
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'reserva_id' => 'required|exists:reservas,id',
                'metodo_pago' => 'required|in:EFECTIVO,TARJETA,BIZUM,TRANSFERENCIA',
                'importe' => 'required|numeric|min:0',
            ]);

            $reserva = Reserva::findOrFail($request->reserva_id);

            // Verificar si ya fue completada
            if ($reserva->estado === 'COMPLETADA') {
                return response()->json([
                    'success' => false,
                    'message' => 'Esta reserva ya ha sido completada'
                ], 400);
            }

            // Verificar si está en estado válido (ahora solo CONFIRMADA)
            if ($reserva->estado !== 'CONFIRMADA') {
                return response()->json([
                    'success' => false,
                    'message' => 'El estado de la reserva no permite procesar el pago'
                ], 400);
            }

            // Crear el pago
            $pago = Pago::create([
                'reserva_id' => $request->reserva_id,
                'estilista_id' => $reserva->estilista_id,
                'metodo_pago' => $request->metodo_pago,
                'importe' => $request->importe,
                'fecha_pago' => now(),
            ]);

            // Marcar reserva como completada
            $reserva->estado = 'COMPLETADA';
            $reserva->save();

            return response()->json([
                'success' => true,
                'message' => 'Pago procesado correctamente',
                'pago' => $pago
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error al procesar pago: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar el pago: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Exporta los pagos a Excel
     */
    public function exportarExcel(Request $request)
    {
        $query = Pago::with(['reserva.servicio', 'reserva.user', 'estilista']);

        // Aplicar los mismos filtros que en el método index
        if ($request->has('fecha_desde') && $request->fecha_desde != '') {
            $query->whereDate('fecha_pago', '>=', $request->fecha_desde);
        }
        
        if ($request->has('fecha_hasta') && $request->fecha_hasta != '') {
            $query->whereDate('fecha_pago', '<=', $request->fecha_hasta);
        }
        
        if ($request->has('importe_min') && $request->importe_min != '') {
            $query->where('importe', '>=', $request->importe_min);
        }
        
        if ($request->has('importe_max') && $request->importe_max != '') {
            $query->where('importe', '<=', $request->importe_max);
        }
        
        if ($request->has('metodo_pago') && $request->metodo_pago != '') {
            $query->where('metodo_pago', $request->metodo_pago);
        }
        
        if ($request->has('servicio_id') && $request->servicio_id != '') {
            $query->whereHas('reserva', function($q) use ($request) {
                $q->where('servicio_id', $request->servicio_id);
            });
        }
        
        if ($request->has('estilista_id') && $request->estilista_id != '') {
            $query->where('estilista_id', $request->estilista_id);
        }
        
        if ($request->has('cliente_id') && $request->cliente_id != '') {
            $query->whereHas('reserva', function($q) use ($request) {
                $q->where('user_id', $request->cliente_id);
            });
        }

        $pagos = $query->get();

        // Aquí implementarías la lógica para exportar a Excel
        // Por ejemplo, usando Laravel Excel o una librería similar
        
        return redirect()->back()->with('success', 'Exportación iniciada');
    }

    public function home()
    {
        // Obtener la fecha de hace un mes
        $fechaInicio = Carbon::now()->subMonth();
        
        // Obtener la fecha de hoy
        $hoy = Carbon::today();
        
        // Pagos de hoy
        $pagosDiaActual = Pago::where('fecha_pago', '>=', $hoy)
            ->get();
            
        $estadisticasHoy = [
            'total_dia' => $pagosDiaActual->sum('importe'),
            'cantidad_pagos' => $pagosDiaActual->count(),
            'promedio_pago' => $pagosDiaActual->avg('importe'),
            'hora_ultimo_pago' => $pagosDiaActual->max('fecha_pago'),
        ];

        // Gráfico 1: Pagos por día del último mes
        $pagosPorDia = Pago::select(
            DB::raw('DATE(fecha_pago) as fecha'),
            DB::raw('SUM(importe) as total')
        )
        ->where('fecha_pago', '>=', $fechaInicio)
        ->groupBy('fecha')
        ->orderBy('fecha')
        ->get();

        // Gráfico 2: Pagos por método de pago
        $pagosPorMetodo = Pago::select(
            'metodo_pago',
            DB::raw('COUNT(*) as cantidad'),
            DB::raw('SUM(importe) as total')
        )
        ->where('fecha_pago', '>=', $fechaInicio)
        ->groupBy('metodo_pago')
        ->get();

        // Gráfico 3: Pagos por estilista
        $pagosPorEstilista = Pago::select(
            'estilistas.nombre',
            DB::raw('COUNT(*) as cantidad'),
            DB::raw('SUM(pagos.importe) as total')
        )
        ->join('estilistas', 'pagos.estilista_id', '=', 'estilistas.id')
        ->where('pagos.fecha_pago', '>=', $fechaInicio)
        ->groupBy('estilistas.id', 'estilistas.nombre')
        ->orderBy('total', 'desc')
        ->get();

        // Gráfico 4: Pagos por servicio
        $pagosPorServicio = Pago::select(
            'servicios.nombre',
            DB::raw('COUNT(*) as cantidad'),
            DB::raw('SUM(pagos.importe) as total')
        )
        ->join('reservas', 'pagos.reserva_id', '=', 'reservas.id')
        ->join('servicios', 'reservas.servicio_id', '=', 'servicios.id')
        ->where('pagos.fecha_pago', '>=', $fechaInicio)
        ->groupBy('servicios.id', 'servicios.nombre')
        ->orderBy('total', 'desc')
        ->get();

        // Estadísticas generales
        $estadisticas = [
            'total_mes' => Pago::where('fecha_pago', '>=', $fechaInicio)->sum('importe'),
            'promedio_diario' => Pago::where('fecha_pago', '>=', $fechaInicio)->avg('importe'),
            'total_transacciones' => Pago::where('fecha_pago', '>=', $fechaInicio)->count(),
            'metodo_mas_usado' => Pago::select('metodo_pago', DB::raw('COUNT(*) as total'))
                ->where('fecha_pago', '>=', $fechaInicio)
                ->groupBy('metodo_pago')
                ->orderBy('total', 'desc')
                ->first()
        ];

        return view('pagos.home', compact(
            'pagosPorDia',
            'pagosPorMetodo',
            'pagosPorEstilista',
            'pagosPorServicio',
            'estadisticas',
            'estadisticasHoy',
            'pagosDiaActual'
        ));
    }

    /**
     * Muestra el formulario para editar un pago específico.
     */
    public function edit(Pago $pago)
    {
        // Cargar las relaciones necesarias
        $pago->load(['reserva.user', 'reserva.servicio', 'estilista']);
        
        // Obtener datos para los selectores
        $estilistas = Estilista::all();
        $servicios = Servicio::all();
        $metodosPago = ['EFECTIVO', 'TARJETA', 'BIZUM', 'TRANSFERENCIA'];
        
        return view('pagos.edit', compact('pago', 'estilistas', 'servicios', 'metodosPago'));
    }

    /**
     * Actualiza un pago específico en la base de datos.
     */
    public function update(Request $request, Pago $pago)
    {
        $request->validate([
            'reserva_id' => 'required|exists:reservas,id',
            'estilista_id' => 'required|exists:estilistas,id',
            'metodo_pago' => 'required|in:EFECTIVO,TARJETA,BIZUM,TRANSFERENCIA',
            'importe' => 'required|numeric|min:0',
            'fecha_pago' => 'required|date',
        ]);

        try {
            $pago->update($request->all());
            
            return redirect()->route('pagos.index')
                ->with('success', 'Pago actualizado correctamente');
        } catch (\Exception $e) {
            Log::error('Error al actualizar pago: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error al actualizar el pago: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Obtiene los detalles de un pago en formato JSON para mostrarlos en un modal
     */
    public function getDetalles(Pago $pago)
    {
        try {
            // Verificar autenticación y obtener información del usuario
            $user = auth()->user();
            \Log::info('Estado de autenticación:', [
                'is_authenticated' => auth()->check(),
                'user_id' => $user ? $user->id : null,
                'user_email' => $user ? $user->email : null,
                'session_id' => session()->getId(),
                'request_path' => request()->path(),
                'request_method' => request()->method(),
            ]);

            if (!auth()->check()) {
                \Log::error('Usuario no autenticado intentando acceder a detalles del pago');
                return response()->json([
                    'error' => 'No autorizado',
                    'message' => 'Debe iniciar sesión para acceder a esta información'
                ], 401);
            }

            // Cargar las relaciones necesarias
            $pago->load(['reserva.servicio', 'reserva.user', 'estilista']);
            
            // Verificar que todas las relaciones necesarias existen
            if (!$pago->reserva) {
                \Log::error('Reserva no encontrada para el pago: ' . $pago->id);
                return response()->json([
                    'error' => 'Reserva no encontrada'
                ], 404);
            }

            if (!$pago->reserva->servicio) {
                \Log::error('Servicio no encontrado para la reserva: ' . $pago->reserva->id);
                return response()->json([
                    'error' => 'Servicio no encontrado'
                ], 404);
            }

            if (!$pago->reserva->user) {
                \Log::error('Usuario no encontrado para la reserva: ' . $pago->reserva->id);
                return response()->json([
                    'error' => 'Usuario no encontrado'
                ], 404);
            }

            if (!$pago->estilista) {
                \Log::error('Estilista no encontrado para el pago: ' . $pago->id);
                return response()->json([
                    'error' => 'Estilista no encontrado'
                ], 404);
            }
            
            // Formatear las fechas para mostrarlas en el modal
            $pago->fecha_pago_formatted = $pago->fecha_pago->format('d/m/Y H:i');
            $pago->reserva->fecha_formatted = $pago->reserva->fecha->format('d/m/Y');
            
            // Calcular la diferencia entre el precio del servicio y el importe pagado
            $diferencia = $pago->importe - $pago->reserva->servicio->precio;
            
            return response()->json([
                'pago' => [
                    'id' => $pago->id,
                    'fecha_pago' => $pago->fecha_pago_formatted,
                    'metodo_pago' => $pago->metodo_pago,
                    'importe' => number_format($pago->importe, 2),
                ],
                'reserva' => [
                    'id' => $pago->reserva->id,
                    'fecha' => $pago->reserva->fecha_formatted,
                    'hora' => $pago->reserva->hora,
                    'estado' => $pago->reserva->estado,
                ],
                'servicio' => [
                    'nombre' => $pago->reserva->servicio->nombre,
                    'precio' => number_format($pago->reserva->servicio->precio, 2),
                    'duracion' => $pago->reserva->servicio->duracion,
                ],
                'cliente' => [
                    'name' => $pago->reserva->user->name,
                    'email' => $pago->reserva->user->email,
                ],
                'estilista' => [
                    'nombre' => $pago->estilista->nombre,
                ],
                'diferencia' => number_format($diferencia, 2),
            ]);
        } catch (\Exception $e) {
            \Log::error('Error al obtener detalles del pago: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'error' => 'Error al obtener los detalles del pago: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Genera un PDF con los detalles del pago
     */
    public function generarPDF(Pago $pago)
    {
        $pago->load(['reserva.servicio', 'reserva.user', 'estilista']);
        
        // Calcular la diferencia entre el precio del servicio y el importe pagado
        $diferencia = $pago->importe - $pago->reserva->servicio->precio;
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pagos.pdf', [
            'pago' => $pago,
            'diferencia' => $diferencia,
        ]);
        
        return $pdf->download('pago-' . $pago->id . '.pdf');
    }
}
