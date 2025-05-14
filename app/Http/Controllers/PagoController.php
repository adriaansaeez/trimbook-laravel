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
use IcehouseVentures\LaravelChartjs\Facades\Chartjs;
use App\Services\ChartHelper;


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
            $user = auth()->user();

            // Verificar permisos: solo admin o estilista asignado
            if (!$user->hasRole('admin') && (!$user->estilista || $user->estilista->id !== $reserva->estilista_id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes permiso para procesar este pago'
                ], 403);
            }

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
        // PERÍODOS DE TIEMPO
        // Período actual (1 mes)
        $fechaInicio = Carbon::now()->startOfMonth();
        $hoy = Carbon::today();
        
        // Período anterior (mes anterior)
        $inicioMesAnterior = Carbon::now()->subMonth()->startOfMonth();
        $finMesAnterior = Carbon::now()->subMonth()->endOfMonth();
        
        // Período de varios meses para evolución intermensual (6 meses)
        $inicioEvolucion = Carbon::now()->subMonths(6)->startOfMonth();
        
        // ESTADÍSTICAS DEL DÍA ACTUAL
        $pagosDiaActual = Pago::where('fecha_pago', '>=', $hoy)->get();
            
        $estadisticasHoy = [
            'total_dia' => $pagosDiaActual->sum('importe'),
            'cantidad_pagos' => $pagosDiaActual->count(),
            'promedio_pago' => $pagosDiaActual->avg('importe') ?: 0,
            'hora_ultimo_pago' => $pagosDiaActual->max('fecha_pago'),
        ];

        // ESTADÍSTICAS DEL MES ACTUAL
        $pagosMesActual = Pago::whereBetween('fecha_pago', [$fechaInicio, $hoy])->get();
        $totalMesActual = $pagosMesActual->sum('importe');
        $cantidadMesActual = $pagosMesActual->count();
        $promedioMesActual = $cantidadMesActual > 0 ? $totalMesActual / $cantidadMesActual : 0;
        
        // ESTADÍSTICAS DEL MES ANTERIOR
        $pagosMesAnterior = Pago::whereBetween('fecha_pago', [$inicioMesAnterior, $finMesAnterior])->get();
        $totalMesAnterior = $pagosMesAnterior->sum('importe');
        $cantidadMesAnterior = $pagosMesAnterior->count();
        $promedioMesAnterior = $cantidadMesAnterior > 0 ? $totalMesAnterior / $cantidadMesAnterior : 0;
        
        // COMPARATIVAS CON PERÍODO ANTERIOR
        $comparativas = [
            'variacion_ingresos' => $totalMesAnterior > 0 ? (($totalMesActual - $totalMesAnterior) / $totalMesAnterior) * 100 : 100,
            'variacion_cantidad' => $cantidadMesAnterior > 0 ? (($cantidadMesActual - $cantidadMesAnterior) / $cantidadMesAnterior) * 100 : 100,
            'variacion_promedio' => $promedioMesAnterior > 0 ? (($promedioMesActual - $promedioMesAnterior) / $promedioMesAnterior) * 100 : 100,
            'diferencia_ingresos' => $totalMesActual - $totalMesAnterior,
            'diferencia_cantidad' => $cantidadMesActual - $cantidadMesAnterior,
            'diferencia_promedio' => $promedioMesActual - $promedioMesAnterior,
        ];
        
        // TOP 5 ESTILISTAS POR INGRESOS (MES ACTUAL)
        $topEstilistas = Pago::select(
            'estilistas.id',
            'estilistas.nombre',
            DB::raw('COUNT(*) as cantidad_pagos'),
            DB::raw('SUM(pagos.importe) as total_ingresos'),
            DB::raw('AVG(pagos.importe) as ticket_medio')
        )
        ->join('estilistas', 'pagos.estilista_id', '=', 'estilistas.id')
        ->whereBetween('pagos.fecha_pago', [$fechaInicio, $hoy])
        ->groupBy('estilistas.id', 'estilistas.nombre')
        ->orderBy('total_ingresos', 'desc')
        ->take(5)
        ->get();
        
        // DISTRIBUCIÓN DE MÉTODOS DE PAGO (MES ACTUAL)
        $pagosPorMetodo = Pago::select(
            'metodo_pago',
            DB::raw('COUNT(*) as cantidad'),
            DB::raw('SUM(importe) as total')
        )
        ->whereBetween('fecha_pago', [$fechaInicio, $hoy])
        ->groupBy('metodo_pago')
        ->get();
        
        // Añadir porcentajes
        foreach ($pagosPorMetodo as $metodo) {
            $metodo->porcentaje = $cantidadMesActual > 0 ? ($metodo->cantidad / $cantidadMesActual) * 100 : 0;
        }
        
        // EVOLUCIÓN INTERMENSUAL (6 MESES)
        $evolucionMensual = Pago::select(
            DB::raw('YEAR(fecha_pago) as anio'),
            DB::raw('MONTH(fecha_pago) as mes'),
            DB::raw('SUM(importe) as total')
        )
        ->where('fecha_pago', '>=', $inicioEvolucion)
        ->groupBy('anio', 'mes')
        ->orderBy('anio')
        ->orderBy('mes')
        ->get();
        
        // Formatear datos de evolución para el gráfico
        $mesesEvolucion = [];
        $valoresEvolucion = [];
        $variacionesMensuales = [];
        
        $mesAnteriorTotal = null;
        
        foreach ($evolucionMensual as $index => $mes) {
            $fechaMes = Carbon::createFromDate($mes->anio, $mes->mes, 1);
            $mesesEvolucion[] = $fechaMes->format('M y');
            $valoresEvolucion[] = $mes->total;
            
            // Calcular variación respecto al mes anterior
            if ($index > 0 && $mesAnteriorTotal > 0) {
                $variacion = (($mes->total - $mesAnteriorTotal) / $mesAnteriorTotal) * 100;
                $variacionesMensuales[] = $variacion;
            } else {
                $variacionesMensuales[] = 0;
            }
            
            $mesAnteriorTotal = $mes->total;
        }

        // GRÁFICO 1: EVOLUCIÓN DE INGRESOS POR MES
        $evolucionMensualChart = Chartjs::build()
            ->name("evolucionMensualChart")
            ->type("line")
            ->size(["width" => 400, "height" => 200])
            ->labels($mesesEvolucion)
            ->datasets([
                [
                    'label' => 'Ingresos mensuales',
                    'data' => $valoresEvolucion,
                    'borderColor' => '#3B82F6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'borderWidth' => 2,
                    'fill' => true,
                    'tension' => 0.4,
                    'pointRadius' => 4,
                    'pointBackgroundColor' => '#3B82F6'
                ],
                [
                    'label' => 'Variación mensual (%)',
                    'data' => $variacionesMensuales,
                    'borderColor' => '#F59E0B',
                    'backgroundColor' => 'rgba(245, 158, 11, 0.1)',
                    'borderWidth' => 2,
                    'fill' => false,
                    'tension' => 0.4,
                    'pointRadius' => 4,
                    'pointBackgroundColor' => '#F59E0B',
                    'yAxisID' => 'y1'
                ]
            ])
            ->options([
                'scales' => [
                    'y' => [
                        'type' => 'linear',
                        'display' => true,
                        'position' => 'left',
                        'beginAtZero' => true,
                        'ticks' => [
                            'callback' => 'function(val) {
                                return "€" + val.toFixed(2);
                            }'
                        ]
                    ],
                    'y1' => [
                        'type' => 'linear',
                        'display' => true,
                        'position' => 'right',
                        'beginAtZero' => false,
                        'ticks' => [
                            'callback' => 'function(val) {
                                return val.toFixed(1) + "%";
                            }'
                        ],
                        'grid' => [
                            'drawOnChartArea' => false
                        ]
                    ],
                    'x' => [
                        'grid' => [
                            'display' => false
                        ]
                    ]
                ]
            ]);

        // GRÁFICO 2: PAGOS POR DÍA (ÚLTIMO MES)
        $pagosPorDia = Pago::select(
            DB::raw('DATE(fecha_pago) as fecha'),
            DB::raw('SUM(importe) as total')
        )
        ->where('fecha_pago', '>=', $fechaInicio)
        ->groupBy('fecha')
        ->orderBy('fecha')
        ->get();

        $fechasFormateadas = $pagosPorDia->map(function($item) {
            return Carbon::parse($item->fecha)->format('d/m');
        })->toArray();
        
        $pagosPorDiaChart = Chartjs::build()
            ->name("pagosPorDiaChart")
            ->type("line")
            ->size(["width" => 400, "height" => 200])
            ->labels($fechasFormateadas)
            ->datasets([
                [
                    'label' => 'Total de Pagos',
                    'data' => $pagosPorDia->pluck('total')->toArray(),
                    'borderColor' => '#3B82F6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'borderWidth' => 2,
                    'fill' => true,
                    'tension' => 0.4,
                    'pointRadius' => 4,
                    'pointBackgroundColor' => '#3B82F6'
                ]
            ])
            ->options([
                'scales' => [
                    'y' => [
                        'beginAtZero' => true,
                        'ticks' => [
                            'callback' => 'function(val) {
                                return "€" + val.toFixed(2);
                            }'
                        ]
                    ],
                    'x' => [
                        'grid' => [
                            'display' => false
                        ]
                    ]
                ]
            ]);
        
        // GRÁFICO 3: PAGOS POR MÉTODO
        $pagosPorMetodoChart = Chartjs::build()
            ->name("pagosPorMetodoChart")
            ->type("pie")
            ->size(["width" => 400, "height" => 200])
            ->labels($pagosPorMetodo->pluck('metodo_pago')->toArray())
            ->datasets([
                [
                    'data' => $pagosPorMetodo->pluck('total')->toArray(),
                    'backgroundColor' => [
                        '#3B82F6', // blue
                        '#10B981', // green
                        '#F59E0B', // yellow
                        '#EF4444'  // red
                    ],
                    'borderWidth' => 0
                ]
            ])
            ->options([
                'plugins' => [
                    'tooltip' => [
                        'callbacks' => [
                            'label' => 'function(context) {
                                const val = context.raw;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = total > 0 ? ((val * 100) / total).toFixed(1) : 0;
                                return `€${val.toFixed(2)} (${percentage}%)`;
                            }'
                        ]
                    ]
                ]
            ]);
        
        // GRÁFICO 4: PAGOS POR ESTILISTA
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

        $pagosPorEstilistaChart = Chartjs::build()
            ->name("pagosPorEstilistaChart")
            ->type("bar")
            ->size(["width" => 400, "height" => 200])
            ->labels($pagosPorEstilista->pluck('nombre')->toArray())
            ->datasets([
                [
                    'label' => 'Total de Pagos',
                    'data' => $pagosPorEstilista->pluck('total')->toArray(),
                    'backgroundColor' => '#4F46E5',
                    'borderRadius' => 6
                ]
            ])
            ->options([
                'scales' => [
                    'y' => [
                        'beginAtZero' => true,
                        'ticks' => [
                            'callback' => 'function(val) {
                                return "€" + val.toFixed(2);
                            }'
                        ]
                    ],
                    'x' => [
                        'grid' => [
                            'display' => false
                        ]
                    ]
                ]
            ]);
        
        // GRÁFICO 5: PAGOS POR SERVICIO
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
        
        $pagosPorServicioChart = Chartjs::build()
            ->name("pagosPorServicioChart")
            ->type("doughnut")
            ->size(["width" => 400, "height" => 200])
            ->labels($pagosPorServicio->pluck('nombre')->toArray())
            ->datasets([
                [
                    'data' => $pagosPorServicio->pluck('total')->toArray(),
                    'backgroundColor' => [
                        '#3B82F6', // blue
                        '#10B981', // green
                        '#F59E0B', // yellow
                        '#EF4444', // red
                        '#8B5CF6', // purple
                        '#EC4899', // pink
                        '#F97316', // orange
                        '#14B8A6'  // teal
                    ],
                    'borderWidth' => 0
                ]
            ])
            ->options([
                'cutout' => '60%',
                'plugins' => [
                    'tooltip' => [
                        'callbacks' => [
                            'label' => 'function(context) {
                                const val = context.raw;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = total > 0 ? ((val * 100) / total).toFixed(1) : 0;
                                return `€${val.toFixed(2)} (${percentage}%)`;
                            }'
                        ]
                    ]
                ]
            ]);

        // Estadísticas generales
        $estadisticas = [
            'total_mes' => $totalMesActual,
            'promedio_diario' => $promedioMesActual,
            'total_transacciones' => $cantidadMesActual,
            'metodo_mas_usado' => $pagosPorMetodo->sortByDesc('cantidad')->first(),
            'inicio_mes' => $fechaInicio->format('d/m/Y'),
            'periodo_actual' => $fechaInicio->format('d M') . ' - ' . $hoy->format('d M, Y')
        ];

        return view('pagos.home', compact(
            'pagosPorDia',
            'pagosPorMetodo',
            'pagosPorEstilista',
            'pagosPorServicio',
            'estadisticas',
            'estadisticasHoy',
            'pagosDiaActual',
            'pagosPorDiaChart',
            'pagosPorMetodoChart',
            'pagosPorEstilistaChart',
            'pagosPorServicioChart',
            'evolucionMensualChart',
            'evolucionMensual',
            'topEstilistas',
            'comparativas'
        ));
    }

    /**
     * Muestra estadísticas de pagos por rango de fechas personalizado
     */
    public function estadisticasRango(Request $request)
    {
        $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ]);

        $fechaInicio = Carbon::parse($request->fecha_inicio)->startOfDay();
        $fechaFin = Carbon::parse($request->fecha_fin)->endOfDay();
        
        // Obtener todos los pagos en el rango de fechas
        $pagos = Pago::whereBetween('fecha_pago', [$fechaInicio, $fechaFin])->get();
        
        // 1. Ingresos totales
        $ingresosTotales = $pagos->sum('importe');
        
        // 2. Número total de pagos
        $totalPagos = $pagos->count();
        
        // Verificar si hay datos para el período seleccionado
        if ($totalPagos == 0) {
            // Establecer valores predeterminados para las variables que se pasan a la vista
            $importeMedio = 0;
            $ingresosPorEstilista = collect();
            $pagosPorMetodo = collect();
            $topEstilistas = collect();
            $pagosPorDia = collect();
            $pagosPorServicio = collect();
            
            // Crear gráficos vacíos
            $metodosPagoChart = ChartHelper::emptyChart();
            $ingresosEstilistaChart = ChartHelper::emptyChart();
            $ticketMedioEstilistaChart = ChartHelper::emptyChart();
            $evolucionPagosChart = ChartHelper::emptyChart();
            $serviciosChart = ChartHelper::emptyChart();
            
            // Flash mensaje para informar al usuario
            session()->flash('info', 'No hay datos de pagos para el período seleccionado: ' . 
                $fechaInicio->format('d/m/Y') . ' - ' . $fechaFin->format('d/m/Y'));
            
            return view('pagos.estadisticas-rango', compact(
                'fechaInicio',
                'fechaFin',
                'ingresosTotales',
                'totalPagos',
                'importeMedio',
                'ingresosPorEstilista',
                'pagosPorMetodo',
                'topEstilistas',
                'pagosPorDia',
                'pagosPorServicio',
                'metodosPagoChart',
                'ingresosEstilistaChart',
                'ticketMedioEstilistaChart',
                'evolucionPagosChart',
                'serviciosChart'
            ));
        }
        
        // 3. Importe medio por pago
        $importeMedio = $ingresosTotales / $totalPagos;
        
        // 4. Ingresos por estilista
        $ingresosPorEstilista = Pago::select(
            'estilistas.id',
            'estilistas.nombre',
            DB::raw('COUNT(*) as cantidad_pagos'),
            DB::raw('SUM(pagos.importe) as total_ingresos'),
            DB::raw('AVG(pagos.importe) as ticket_medio')
        )
        ->join('estilistas', 'pagos.estilista_id', '=', 'estilistas.id')
        ->whereBetween('pagos.fecha_pago', [$fechaInicio, $fechaFin])
        ->groupBy('estilistas.id', 'estilistas.nombre')
        ->orderBy('total_ingresos', 'desc')
        ->get();
        
        // 5. Distribución de métodos de pago
        $pagosPorMetodo = Pago::select(
            'metodo_pago',
            DB::raw('COUNT(*) as cantidad'),
            DB::raw('SUM(importe) as total')
        )
        ->whereBetween('fecha_pago', [$fechaInicio, $fechaFin])
        ->groupBy('metodo_pago')
        ->get();
        
        // Calcular porcentajes para métodos de pago
        foreach ($pagosPorMetodo as $metodo) {
            $metodo->porcentaje = ($metodo->cantidad / $totalPagos) * 100;
        }
        
        // 6. Top 5 estilistas por ingresos
        $topEstilistas = $ingresosPorEstilista->take(5);
        
        // 7. Pagos por día en el rango seleccionado
        $pagosPorDia = Pago::select(
            DB::raw('DATE(fecha_pago) as fecha'),
            DB::raw('SUM(importe) as total')
        )
        ->whereBetween('fecha_pago', [$fechaInicio, $fechaFin])
        ->groupBy('fecha')
        ->orderBy('fecha')
        ->get();
        
        // 8. Pagos por servicio
        $pagosPorServicio = Pago::select(
            'servicios.nombre',
            DB::raw('COUNT(*) as cantidad'),
            DB::raw('SUM(pagos.importe) as total')
        )
        ->join('reservas', 'pagos.reserva_id', '=', 'reservas.id')
        ->join('servicios', 'reservas.servicio_id', '=', 'servicios.id')
        ->whereBetween('pagos.fecha_pago', [$fechaInicio, $fechaFin])
        ->groupBy('servicios.id', 'servicios.nombre')
        ->orderBy('total', 'desc')
        ->get();
        
        // Crear gráficos usando laravel-chartjs
        
        // Métodos de pago - Gráfico de Pie
        $metodosPagoChart = Chartjs::build()
            ->name("metodosPagoChart")
            ->type("pie")
            ->size(["width" => 400, "height" => 200])
            ->labels($pagosPorMetodo->pluck('metodo_pago')->toArray())
            ->datasets([
                [
                    'data' => $pagosPorMetodo->pluck('total')->toArray(),
                    'backgroundColor' => [
                        '#3B82F6', // blue
                        '#10B981', // green
                        '#F59E0B', // yellow
                        '#EF4444'  // red
                    ],
                    'borderWidth' => 0
                ]
            ])
            ->options([
                'plugins' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Distribución por método de pago',
                        'font' => [
                            'size' => 16
                        ]
                    ],
                    'tooltip' => [
                        'callbacks' => [
                            'label' => 'function(context) {
                                const val = context.raw;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = total > 0 ? ((val * 100) / total).toFixed(1) : 0;
                                return `€${val.toFixed(2)} (${percentage}%)`;
                            }'
                        ]
                    ]
                ]
            ]);
        
        // Ingresos por estilista - Gráfico de Barras
        $ingresosEstilistaChart = Chartjs::build()
            ->name("ingresosEstilistaChart")
            ->type("bar")
            ->size(["width" => 400, "height" => 200])
            ->labels($ingresosPorEstilista->pluck('nombre')->toArray())
            ->datasets([
                [
                    'label' => 'Ingresos totales',
                    'data' => $ingresosPorEstilista->pluck('total_ingresos')->toArray(),
                    'backgroundColor' => '#4F46E5',
                    'borderRadius' => 6
                ]
            ])
            ->options([
                'scales' => [
                    'y' => [
                        'beginAtZero' => true,
                        'ticks' => [
                            'callback' => 'function(val) {
                                return "€" + val.toFixed(2);
                            }'
                        ]
                    ],
                    'x' => [
                        'grid' => [
                            'display' => false
                        ]
                    ]
                ],
                'plugins' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Ingresos por estilista',
                        'font' => [
                            'size' => 16
                        ]
                    ]
                ]
            ]);
        
        // Ticket medio por estilista - Gráfico de Barras Horizontales
        $ticketMedioEstilistaChart = Chartjs::build()
            ->name("ticketMedioEstilistaChart")
            ->type("bar")
            ->size(["width" => 400, "height" => 200])
            ->labels($ingresosPorEstilista->pluck('nombre')->toArray())
            ->datasets([
                [
                    'label' => 'Ticket medio',
                    'data' => $ingresosPorEstilista->pluck('ticket_medio')->toArray(),
                    'backgroundColor' => '#8B5CF6',
                    'borderRadius' => 6
                ]
            ])
            ->options([
                'indexAxis' => 'y',
                'scales' => [
                    'x' => [
                        'beginAtZero' => true,
                        'ticks' => [
                            'callback' => 'function(val) {
                                return "€" + val.toFixed(2);
                            }'
                        ]
                    ],
                    'y' => [
                        'grid' => [
                            'display' => false
                        ]
                    ]
                ],
                'plugins' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Ticket medio por estilista',
                        'font' => [
                            'size' => 16
                        ]
                    ]
                ]
            ]);
        
        // Formatear fechas para el gráfico de evolución
        $fechasFormateadas = $pagosPorDia->map(function($item) {
            return Carbon::parse($item->fecha)->format('d/m');
        })->toArray();
        
        // Evolución de pagos - Gráfico de Línea
        $evolucionPagosChart = Chartjs::build()
            ->name("evolucionPagosChart")
            ->type("line")
            ->size(["width" => 400, "height" => 200])
            ->labels($fechasFormateadas)
            ->datasets([
                [
                    'label' => 'Total diario',
                    'data' => $pagosPorDia->pluck('total')->toArray(),
                    'borderColor' => '#3B82F6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'borderWidth' => 2,
                    'fill' => true,
                    'tension' => 0.4,
                    'pointRadius' => 4,
                    'pointBackgroundColor' => '#3B82F6'
                ]
            ])
            ->options([
                'scales' => [
                    'y' => [
                        'beginAtZero' => true,
                        'ticks' => [
                            'callback' => 'function(val) {
                                return "€" + val.toFixed(2);
                            }'
                        ]
                    ],
                    'x' => [
                        'grid' => [
                            'display' => false
                        ]
                    ]
                ],
                'plugins' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Evolución de ingresos por día',
                        'font' => [
                            'size' => 16
                        ]
                    ]
                ]
            ]);
        
        // Servicios - Gráfico de Donut
        $serviciosChart = Chartjs::build()
            ->name("serviciosChart")
            ->type("doughnut")
            ->size(["width" => 400, "height" => 200])
            ->labels($pagosPorServicio->pluck('nombre')->toArray())
            ->datasets([
                [
                    'data' => $pagosPorServicio->pluck('total')->toArray(),
                    'backgroundColor' => [
                        '#3B82F6', // blue
                        '#10B981', // green
                        '#F59E0B', // yellow
                        '#EF4444', // red
                        '#8B5CF6', // purple
                        '#EC4899', // pink
                        '#F97316', // orange
                        '#14B8A6'  // teal
                    ],
                    'borderWidth' => 0
                ]
            ])
            ->options([
                'cutout' => '60%',
                'plugins' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Ingresos por servicio',
                        'font' => [
                            'size' => 16
                        ]
                    ],
                    'tooltip' => [
                        'callbacks' => [
                            'label' => 'function(context) {
                                const val = context.raw;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = total > 0 ? ((val * 100) / total).toFixed(1) : 0;
                                return `€${val.toFixed(2)} (${percentage}%)`;
                            }'
                        ]
                    ]
                ]
            ]);
        
        return view('pagos.estadisticas-rango', compact(
            'fechaInicio',
            'fechaFin',
            'ingresosTotales',
            'totalPagos',
            'importeMedio',
            'ingresosPorEstilista',
            'pagosPorMetodo',
            'topEstilistas',
            'pagosPorDia',
            'pagosPorServicio',
            'metodosPagoChart',
            'ingresosEstilistaChart',
            'ticketMedioEstilistaChart',
            'evolucionPagosChart',
            'serviciosChart'
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
