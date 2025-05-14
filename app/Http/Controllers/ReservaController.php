<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reserva;
use App\Models\Servicio;
use App\Models\Estilista;
use App\Models\Horario;
use App\Models\Pago;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use PDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReservasExport;
use Illuminate\Support\Facades\DB;
use IcehouseVentures\LaravelChartjs\Facades\Chartjs;
use App\Services\ChartHelper;

class ReservaController extends Controller
{
    public function index(Request $request)
    {
        $query = Reserva::query();
        
        // Filtrar por usuario autenticado si no es admin
        if (!Auth::user()->hasRole('admin')) {
            if (Auth::user()->hasRole('estilista')) {
                $estilista = Auth::user()->estilista;
                $query->where('estilista_id', $estilista->id);
            } else {
                $query->where('user_id', Auth::id());
            }
        }
        
        // Filtros
        if ($request->has('servicio') && $request->servicio != '') {
            $query->where('servicio_id', $request->servicio);
        }
        
        if ($request->has('estilista') && $request->estilista != '') {
            $query->where('estilista_id', $request->estilista);
        }
        
        if ($request->has('fecha_desde') && $request->fecha_desde != '') {
            $query->where('fecha', '>=', $request->fecha_desde);
        }
        
        if ($request->has('fecha_hasta') && $request->fecha_hasta != '') {
            $query->where('fecha', '<=', $request->fecha_hasta);
        }
        
        if ($request->has('estado') && $request->estado != '') {
            $query->where('estado', $request->estado);
        }
        
        // Ordenación
        $ordenarPor = $request->input('ordenar_por', 'fecha');
        $orden = $request->input('orden', 'desc');
        $query->orderBy($ordenarPor, $orden);
        
        // Cargar relaciones
        $query->with(['servicio', 'estilista', 'user', 'pago']);
        
        // Obtener resultados
        $reservas = $query->paginate(10);
        
        // Datos para los filtros
        $servicios = Servicio::all();
        $estilistas = Estilista::all();
        $estados = ['PENDIENTE', 'CONFIRMADA', 'CANCELADA', 'COMPLETADA'];
        
        return view('reservas.index', compact('reservas', 'servicios', 'estilistas', 'estados'));
    }

    public function create()
    {
        $servicios = Servicio::all();
        return view('reservas.create', compact('servicios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'servicio_id'  => 'required|exists:servicios,id',
            'estilista_id' => 'required|exists:estilistas,id',
            'fecha'        => 'required|date|after_or_equal:today',
            'hora'         => [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    if (Reserva::where('estilista_id', $request->estilista_id)
                        ->where('fecha', $request->fecha)
                        ->where('hora', $value)
                        ->whereNotIn('estado', ['CANCELADA']) // Excluir reservas canceladas
                        ->exists()
                    ) {
                        $fail('La hora seleccionada ya está reservada.');
                    }
                },
            ],
        ]);

        // Crear reserva en BD
        $fecha = Carbon::parse($request->fecha)->format('Y-m-d');
        $reserva = Reserva::create([
            'user_id'      => auth()->id(),
            'servicio_id'  => $request->servicio_id,
            'estilista_id' => $request->estilista_id,
            'fecha'        => $fecha,
            'hora'         => $request->hora,
        ]);


        return redirect()->route('reservas.index')
                        ->with('success', 'Reserva creada correctamente y notificación enviada.');
    }

    public function show(Reserva $reserva)
    {
        // Verificar permisos
        if (!Auth::user()->hasRole('admin') && 
            Auth::id() !== $reserva->user_id && 
            (!Auth::user()->hasRole('estilista') || Auth::user()->estilista->id !== $reserva->estilista_id)) {
            return redirect()->route('reservas.index')->with('error', 'No tienes permiso para ver esta reserva.');
        }
        
        return view('reservas.show', compact('reserva'));
    }

    public function edit(Reserva $reserva)
    {
        // Solo admin puede editar cualquier reserva
        if (!Auth::user()->hasRole('admin')) {
            return redirect()->route('reservas.index')->with('error', 'No tienes permiso para editar esta reserva.');
        }
        
        $servicios = Servicio::all();
        $estilistas = Estilista::all();
        $users = \App\Models\User::with('perfil')->get();
        $estados = ['PENDIENTE', 'CONFIRMADA', 'CANCELADA', 'COMPLETADA'];
        
        return view('reservas.edit', compact('reserva', 'servicios', 'estilistas', 'users', 'estados'));
    }

    public function update(Request $request, Reserva $reserva)
    {
        // Solo admin puede actualizar cualquier reserva
        if (!Auth::user()->hasRole('admin')) {
            return redirect()->route('reservas.index')->with('error', 'No tienes permiso para actualizar esta reserva.');
        }
        
        $request->validate([
            'servicio_id'  => 'required|exists:servicios,id',
            'estilista_id' => 'required|exists:estilistas,id',
            'fecha'        => 'required|date',
            'hora'         => 'required',
            'estado'       => 'required|in:PENDIENTE,CONFIRMADA,CANCELADA,COMPLETADA',
        ]);
        
        $reserva->update($request->all());
        
        return redirect()->route('reservas.index')->with('success', 'Reserva actualizada correctamente.');
    }

    public function destroy(Reserva $reserva)
    {
        // Verificar que el usuario autenticado es el dueño de la reserva o un admin
        if (auth()->user()->hasRole('admin') || auth()->id() === $reserva->user_id) {
            $reserva->delete();
            return redirect()->route('reservas.index')->with('success', 'Reserva cancelada correctamente.');
        }

        return redirect()->route('reservas.index')->withErrors(['error' => 'No tienes permiso para cancelar esta reserva.']);
    }

    // Obtener estilistas que pueden realizar un servicio
    public function getEstilistas($servicio_id)
    {
        $estilistas = Estilista::whereHas('servicios', function ($query) use ($servicio_id) {
            $query->where('servicios.id', $servicio_id); // Especificamos la tabla 'servicios'
        })->get();

        return response()->json($estilistas);
    }

    public function cambiarEstado(Request $request, Reserva $reserva)
    {
        $user = auth()->user();

        if (!$user->estilista || $user->estilista->id !== $reserva->estilista_id) {
            return redirect()->back()->with('error', 'No autorizado');
        }

        $validated = $request->validate([
            'estado' => 'required|in:PENDIENTE,CONFIRMADA,CANCELADA,COMPLETADA'
        ]);

        $reserva->estado = $validated['estado'];
        $reserva->save();

        return redirect()->back()->with('success', 'Estado actualizado correctamente');
    }

    public function cancelar(Reserva $reserva)
    {
        $user = auth()->user();

        // Permitir cancelar si es admin, dueño de la reserva, o estilista asignado (solo si está confirmada para estilista)
        if (
            $user->hasRole('admin') ||
            $user->id === $reserva->user_id ||
            ($user->hasRole('estilista') && isset($user->estilista) && $user->estilista->id === $reserva->estilista_id && $reserva->estado === 'CONFIRMADA')
        ) {
            $reserva->estado = 'CANCELADA';
            $reserva->save();
            return redirect()->back()->with('success', 'Reserva cancelada con éxito');
        }

        return redirect()->back()->with('error', 'No autorizado');
    }

    public function confirmar(Reserva $reserva)
    {
        $user = auth()->user();

        // Verificar que el usuario es el estilista asignado a la reserva
        if (!$user->estilista || $user->estilista->id !== $reserva->estilista_id) {
            return redirect()->back()->with('error', 'No autorizado para confirmar esta reserva');
        }

        // Actualizar el estado de la reserva
        $reserva->estado = 'CONFIRMADA';
        $reserva->save();

        return redirect()->back()->with('success', 'Reserva confirmada con éxito');
    }
    
    public function completar(Request $request, Reserva $reserva)
    {
        $user = auth()->user();
        
        // Verificar que el usuario es admin o el estilista asignado a la reserva
        if (!$user->hasRole('admin') && (!$user->estilista || $user->estilista->id !== $reserva->estilista_id)) {
            return redirect()->back()->with('error', 'No autorizado para completar esta reserva');
        }

        // Validar los datos del pago
        $request->validate([
            'metodo_pago' => 'required|in:EFECTIVO,TARJETA,BIZUM,TRANSFERENCIA',
            'importe' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Crear el pago
            Pago::create([
                'reserva_id' => $reserva->id,
                'estilista_id' => $reserva->estilista_id, // Usar el estilista de la reserva
                'metodo_pago' => $request->metodo_pago,
                'importe' => $request->importe,
                'fecha_pago' => now(),
            ]);
            
            // Actualizar el estado de la reserva
            $reserva->estado = 'COMPLETADA';
            $reserva->save();

            DB::commit();
            
            return redirect()->back()->with('success', 'Reserva completada y pago registrado con éxito');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Error al procesar la reserva y el pago: ' . $e->getMessage());
        }
    }
    
    public function registrarPago(Request $request, Reserva $reserva)
    {
        $user = auth()->user();
        
        // Verificar que el usuario es admin o el estilista asignado a la reserva
        if (!$user->hasRole('admin') && (!$user->estilista || $user->estilista->id !== $reserva->estilista_id)) {
            return redirect()->back()->with('error', 'No autorizado para registrar el pago de esta reserva');
        }
        
        $request->validate([
            'metodo_pago' => 'required|in:EFECTIVO,TARJETA,BIZUM,TRANSFERENCIA',
            'importe' => 'required|numeric|min:0',
        ]);
        
        // Crear el pago
        Pago::create([
            'reserva_id' => $reserva->id,
            'estilista_id' => $reserva->estilista_id, // Usar el estilista de la reserva
            'metodo_pago' => $request->metodo_pago,
            'importe' => $request->importe,
            'fecha_pago' => now(),
        ]);
        
        return redirect()->back()->with('success', 'Pago registrado con éxito');
    }
    
    public function exportarPDF(Reserva $reserva)
    {
        // Verificar permisos
        if (!Auth::user()->hasRole('admin') && 
            Auth::id() !== $reserva->user_id && 
            (!Auth::user()->hasRole('estilista') || Auth::user()->estilista->id !== $reserva->estilista_id)) {
            return redirect()->route('reservas.index')->with('error', 'No tienes permiso para exportar esta reserva.');
        }
        
        $pdf = PDF::loadView('reservas.pdf', compact('reserva'));
        
        return $pdf->download('reserva-' . $reserva->id . '.pdf');
    }
    
    public function exportarExcel(Request $request)
    {
        // Verificar permisos
        if (!Auth::user()->hasRole('admin') && !Auth::user()->hasRole('estilista')) {
            return redirect()->route('reservas.index')->with('error', 'No tienes permiso para exportar reservas.');
        }
        
        // Filtrar por estilista si el usuario es estilista
        $estilistaId = null;
        if (Auth::user()->hasRole('estilista')) {
            $estilistaId = Auth::user()->estilista->id;
        }
        
        return Excel::download(new ReservasExport($estilistaId), 'reservas.xlsx');
    }

    public function dashboard()
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
        $reservasHoy = Reserva::whereDate('fecha', $hoy)->get();
            
        $estadisticasHoy = [
            'total_reservas' => $reservasHoy->count(),
            'pendientes' => $reservasHoy->where('estado', 'PENDIENTE')->count(),
            'confirmadas' => $reservasHoy->where('estado', 'CONFIRMADA')->count(),
            'completadas' => $reservasHoy->where('estado', 'COMPLETADA')->count(),
            'canceladas' => $reservasHoy->where('estado', 'CANCELADA')->count(),
            'hora_ultima_reserva' => $reservasHoy->max('created_at'),
        ];

        // ESTADÍSTICAS DEL MES ACTUAL
        $reservasMesActual = Reserva::whereBetween('fecha', [$fechaInicio, $hoy])->get();
        $totalMesActual = $reservasMesActual->count();
        
        // ESTADÍSTICAS DEL MES ANTERIOR
        $reservasMesAnterior = Reserva::whereBetween('fecha', [$inicioMesAnterior, $finMesAnterior])->get();
        $totalMesAnterior = $reservasMesAnterior->count();
        
        // COMPARATIVAS CON PERÍODO ANTERIOR
        $comparativas = [
            'variacion_total' => $totalMesAnterior > 0 ? (($totalMesActual - $totalMesAnterior) / $totalMesAnterior) * 100 : 100,
            'variacion_pendientes' => $reservasMesAnterior->where('estado', 'PENDIENTE')->count() > 0 ? 
                (($reservasMesActual->where('estado', 'PENDIENTE')->count() - $reservasMesAnterior->where('estado', 'PENDIENTE')->count()) / $reservasMesAnterior->where('estado', 'PENDIENTE')->count()) * 100 : 100,
            'variacion_confirmadas' => $reservasMesAnterior->where('estado', 'CONFIRMADA')->count() > 0 ? 
                (($reservasMesActual->where('estado', 'CONFIRMADA')->count() - $reservasMesAnterior->where('estado', 'CONFIRMADA')->count()) / $reservasMesAnterior->where('estado', 'CONFIRMADA')->count()) * 100 : 100,
            'diferencia_total' => $totalMesActual - $totalMesAnterior,
        ];
        
        // TOP 5 ESTILISTAS POR NÚMERO DE RESERVAS
        $topEstilistas = Reserva::select(
            'estilistas.id',
            'estilistas.nombre',
            DB::raw('COUNT(*) as total_reservas')
        )
        ->join('estilistas', 'reservas.estilista_id', '=', 'estilistas.id')
        ->whereBetween('reservas.fecha', [$fechaInicio, $hoy])
        ->groupBy('estilistas.id', 'estilistas.nombre')
        ->orderBy('total_reservas', 'desc')
        ->take(5)
        ->get();
        
        // DISTRIBUCIÓN DE RESERVAS POR ESTADO
        $reservasPorEstado = Reserva::select(
            'estado',
            DB::raw('COUNT(*) as cantidad')
        )
        ->whereBetween('fecha', [$fechaInicio, $hoy])
        ->groupBy('estado')
        ->get();
        
        // Calcular porcentajes
        foreach ($reservasPorEstado as $estado) {
            $estado->porcentaje = $totalMesActual > 0 ? ($estado->cantidad / $totalMesActual) * 100 : 0;
        }
        
        // EVOLUCIÓN INTERMENSUAL DE RESERVAS (6 MESES)
        $evolucionMensual = Reserva::select(
            DB::raw('YEAR(fecha) as anio'),
            DB::raw('MONTH(fecha) as mes'),
            DB::raw('COUNT(*) as total')
        )
        ->where('fecha', '>=', $inicioEvolucion)
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

        // GRÁFICO 1: EVOLUCIÓN DE RESERVAS POR MES
        $evolucionMensualChart = Chartjs::build()
            ->name("evolucionMensualChart")
            ->type("line")
            ->size(["width" => 400, "height" => 200])
            ->labels($mesesEvolucion)
            ->datasets([
                [
                    'label' => 'Reservas mensuales',
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

        // GRÁFICO 2: RESERVAS POR DÍA (ÚLTIMO MES)
        $reservasPorDia = Reserva::select(
            DB::raw('DATE(fecha) as fecha'),
            DB::raw('COUNT(*) as total')
        )
        ->where('fecha', '>=', $fechaInicio)
        ->groupBy('fecha')
        ->orderBy('fecha')
        ->get();

        $fechasFormateadas = $reservasPorDia->map(function($item) {
            return Carbon::parse($item->fecha)->format('d/m');
        })->toArray();
        
        $reservasPorDiaChart = Chartjs::build()
            ->name("reservasPorDiaChart")
            ->type("line")
            ->size(["width" => 400, "height" => 200])
            ->labels($fechasFormateadas)
            ->datasets([
                [
                    'label' => 'Total de Reservas',
                    'data' => $reservasPorDia->pluck('total')->toArray(),
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
                    ],
                    'x' => [
                        'grid' => [
                            'display' => false
                        ]
                    ]
                ]
            ]);
        
        // GRÁFICO 3: RESERVAS POR ESTADO
        $reservasPorEstadoChart = Chartjs::build()
            ->name("reservasPorEstadoChart")
            ->type("pie")
            ->size(["width" => 400, "height" => 200])
            ->labels($reservasPorEstado->pluck('estado')->toArray())
            ->datasets([
                [
                    'data' => $reservasPorEstado->pluck('cantidad')->toArray(),
                    'backgroundColor' => [
                        '#10B981', // verde (CONFIRMADA)
                        '#F59E0B', // amarillo (PENDIENTE)
                        '#3B82F6', // azul (COMPLETADA)
                        '#EF4444'  // rojo (CANCELADA)
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
                                return `${val} (${percentage}%)`;
                            }'
                        ]
                    ]
                ]
            ]);
        
        // GRÁFICO 4: RESERVAS POR ESTILISTA
        $reservasPorEstilista = Reserva::select(
            'estilistas.nombre',
            DB::raw('COUNT(*) as cantidad')
        )
        ->join('estilistas', 'reservas.estilista_id', '=', 'estilistas.id')
        ->where('reservas.fecha', '>=', $fechaInicio)
        ->groupBy('estilistas.id', 'estilistas.nombre')
        ->orderBy('cantidad', 'desc')
        ->get();

        $reservasPorEstilistaChart = Chartjs::build()
            ->name("reservasPorEstilistaChart")
            ->type("bar")
            ->size(["width" => 400, "height" => 200])
            ->labels($reservasPorEstilista->pluck('nombre')->toArray())
            ->datasets([
                [
                    'label' => 'Total de Reservas',
                    'data' => $reservasPorEstilista->pluck('cantidad')->toArray(),
                    'backgroundColor' => '#4F46E5',
                    'borderRadius' => 6
                ]
            ])
            ->options([
                'scales' => [
                    'y' => [
                        'beginAtZero' => true
                    ],
                    'x' => [
                        'grid' => [
                            'display' => false
                        ]
                    ]
                ]
            ]);
        
        // GRÁFICO 5: RESERVAS POR SERVICIO
        $reservasPorServicio = Reserva::select(
            'servicios.nombre',
            DB::raw('COUNT(*) as cantidad')
        )
        ->join('servicios', 'reservas.servicio_id', '=', 'servicios.id')
        ->where('reservas.fecha', '>=', $fechaInicio)
        ->groupBy('servicios.id', 'servicios.nombre')
        ->orderBy('cantidad', 'desc')
        ->get();
        
        $reservasPorServicioChart = Chartjs::build()
            ->name("reservasPorServicioChart")
            ->type("doughnut")
            ->size(["width" => 400, "height" => 200])
            ->labels($reservasPorServicio->pluck('nombre')->toArray())
            ->datasets([
                [
                    'data' => $reservasPorServicio->pluck('cantidad')->toArray(),
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
                                return `${val} (${percentage}%)`;
                            }'
                        ]
                    ]
                ]
            ]);

        // TASA DE CANCELACIÓN
        $tasaCancelacion = $totalMesActual > 0 ? 
            ($reservasMesActual->where('estado', 'CANCELADA')->count() / $totalMesActual) * 100 : 0;
            
        // RESERVAS PRÓXIMAS (PRÓXIMOS 7 DÍAS)
        $fechaFinProximas = Carbon::today()->addDays(7);
        $reservasProximas = Reserva::whereBetween('fecha', [$hoy, $fechaFinProximas])
            ->where('estado', '!=', 'CANCELADA')
            ->orderBy('fecha')
            ->orderBy('hora')
            ->get();
            
        // DISTRIBUCIÓN DE RESERVAS POR CLIENTE (TOP 10)
        $reservasPorCliente = Reserva::select(
            'users.username as name',
            DB::raw('COUNT(*) as total_reservas')
        )
        ->join('users', 'reservas.user_id', '=', 'users.id')
        ->whereBetween('reservas.fecha', [$fechaInicio, $hoy])
        ->groupBy('users.id', 'users.username')
        ->orderBy('total_reservas', 'desc')
        ->take(10)
        ->get();

        // TIEMPO MEDIO DE ANTELACIÓN (DÍAS ENTRE CREACIÓN Y FECHA PROGRAMADA)
        $tiempoMedioAntelacion = $reservasMesActual
            ->avg(function ($reserva) {
                return Carbon::parse($reserva->created_at)->diffInDays(Carbon::parse($reserva->fecha));
            });

        // Estadísticas generales
        $estadisticas = [
            'total_reservas' => $totalMesActual,
            'pendientes' => $reservasMesActual->where('estado', 'PENDIENTE')->count(),
            'confirmadas' => $reservasMesActual->where('estado', 'CONFIRMADA')->count(),
            'completadas' => $reservasMesActual->where('estado', 'COMPLETADA')->count(),
            'canceladas' => $reservasMesActual->where('estado', 'CANCELADA')->count(),
            'tasa_cancelacion' => $tasaCancelacion,
            'tiempo_medio_antelacion' => round($tiempoMedioAntelacion, 1),
            'inicio_mes' => $fechaInicio->format('d/m/Y'),
            'periodo_actual' => $fechaInicio->format('d M') . ' - ' . $hoy->format('d M, Y')
        ];

        return view('reservas.dashboard', compact(
            'reservasPorDia',
            'reservasPorEstado',
            'reservasPorEstilista',
            'reservasPorServicio',
            'estadisticas',
            'estadisticasHoy',
            'reservasHoy',
            'reservasPorDiaChart',
            'reservasPorEstadoChart',
            'reservasPorEstilistaChart',
            'reservasPorServicioChart',
            'evolucionMensualChart',
            'evolucionMensual',
            'topEstilistas',
            'comparativas',
            'reservasProximas',
            'reservasPorCliente'
        ));
    }

    public function estadisticasRango(Request $request)
    {
        $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ]);

        $fechaInicio = Carbon::parse($request->fecha_inicio)->startOfDay();
        $fechaFin = Carbon::parse($request->fecha_fin)->endOfDay();
        
        // Obtener todas las reservas en el rango de fechas
        $reservas = Reserva::whereBetween('fecha', [$fechaInicio, $fechaFin])->get();
        
        // 1. Número total de reservas
        $totalReservas = $reservas->count();
        
        // Verificar si hay datos para el período seleccionado
        if ($totalReservas == 0) {
            // Establecer valores predeterminados para las variables que se pasan a la vista
            $reservasPorEstado = collect();
            $reservasPorEstilista = collect();
            $topEstilistas = collect();
            $reservasPorDia = collect();
            $reservasPorServicio = collect();
            $reservasPorCliente = collect();
            $tasaCancelacion = 0;
            $tiempoMedioAntelacion = 0;
            
            // Crear gráficos vacíos utilizando ChartHelper
            $reservasPorEstadoChart = ChartHelper::emptyChart();
            $reservasPorEstilistaChart = ChartHelper::emptyChart();
            $evolucionReservasChart = ChartHelper::emptyChart();
            $reservasPorServicioChart = ChartHelper::emptyChart();
            
            // Flash mensaje para informar al usuario
            session()->flash('info', 'No hay datos de reservas para el período seleccionado: ' . 
                $fechaInicio->format('d/m/Y') . ' - ' . $fechaFin->format('d/m/Y'));
            
            return view('reservas.estadisticas-rango', compact(
                'fechaInicio',
                'fechaFin',
                'totalReservas',
                'reservasPorEstado',
                'reservasPorEstilista',
                'topEstilistas',
                'reservasPorDia',
                'reservasPorServicio',
                'reservasPorCliente',
                'tasaCancelacion',
                'tiempoMedioAntelacion',
                'reservasPorEstadoChart',
                'reservasPorEstilistaChart',
                'evolucionReservasChart',
                'reservasPorServicioChart'
            ));
        }
        
        // 2. Distribución de reservas por Estado
        $reservasPorEstado = Reserva::select(
            'estado',
            DB::raw('COUNT(*) as cantidad')
        )
        ->whereBetween('fecha', [$fechaInicio, $fechaFin])
        ->groupBy('estado')
        ->get();
        
        // Calcular porcentajes
        foreach ($reservasPorEstado as $estado) {
            $estado->porcentaje = ($estado->cantidad / $totalReservas) * 100;
        }
        
        // 3. Reservas por Estilista
        $reservasPorEstilista = Reserva::select(
            'estilistas.id',
            'estilistas.nombre',
            DB::raw('COUNT(*) as total_reservas')
        )
        ->join('estilistas', 'reservas.estilista_id', '=', 'estilistas.id')
        ->whereBetween('reservas.fecha', [$fechaInicio, $fechaFin])
        ->groupBy('estilistas.id', 'estilistas.nombre')
        ->orderBy('total_reservas', 'desc')
        ->get();
        
        // 4. Top 5 Estilistas por reservas
        $topEstilistas = $reservasPorEstilista->take(5);
        
        // 5. Reservas por día en el rango seleccionado
        $reservasPorDia = Reserva::select(
            DB::raw('DATE(fecha) as fecha'),
            DB::raw('COUNT(*) as total')
        )
        ->whereBetween('fecha', [$fechaInicio, $fechaFin])
        ->groupBy('fecha')
        ->orderBy('fecha')
        ->get();
        
        // 6. Reservas por Servicio
        $reservasPorServicio = Reserva::select(
            'servicios.nombre',
            DB::raw('COUNT(*) as cantidad')
        )
        ->join('servicios', 'reservas.servicio_id', '=', 'servicios.id')
        ->whereBetween('reservas.fecha', [$fechaInicio, $fechaFin])
        ->groupBy('servicios.id', 'servicios.nombre')
        ->orderBy('cantidad', 'desc')
        ->get();
        
        // 7. Tasa de Cancelación
        $tasaCancelacion = ($reservas->where('estado', 'CANCELADA')->count() / $totalReservas) * 100;
        
        // 8. Tiempo Medio de Antelación
        $tiempoMedioAntelacion = $reservas
            ->avg(function ($reserva) {
                return Carbon::parse($reserva->created_at)->diffInDays(Carbon::parse($reserva->fecha));
            });
            
        // 9. Distribución de Reservas por Cliente
        $reservasPorCliente = Reserva::select(
            'users.username as name',
            DB::raw('COUNT(*) as total_reservas')
        )
        ->join('users', 'reservas.user_id', '=', 'users.id')
        ->whereBetween('reservas.fecha', [$fechaInicio, $fechaFin])
        ->groupBy('users.id', 'users.username')
        ->orderBy('total_reservas', 'desc')
        ->take(10)
        ->get();
        
        // Crear gráficos usando laravel-chartjs
        
        // Estados de Reserva - Gráfico de Pie
        $reservasPorEstadoChart = Chartjs::build()
            ->name("reservasPorEstadoChart")
            ->type("pie")
            ->size(["width" => 400, "height" => 200])
            ->labels($reservasPorEstado->pluck('estado')->toArray())
            ->datasets([
                [
                    'data' => $reservasPorEstado->pluck('cantidad')->toArray(),
                    'backgroundColor' => [
                        '#10B981', // verde (CONFIRMADA)
                        '#F59E0B', // amarillo (PENDIENTE)
                        '#3B82F6', // azul (COMPLETADA)
                        '#EF4444'  // rojo (CANCELADA)
                    ],
                    'borderWidth' => 0
                ]
            ])
            ->options([
                'plugins' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Distribución por estado',
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
                                return `${val} (${percentage}%)`;
                            }'
                        ]
                    ]
                ]
            ]);
        
        // Reservas por Estilista - Gráfico de Barras
        $reservasPorEstilistaChart = Chartjs::build()
            ->name("reservasPorEstilistaChart")
            ->type("bar")
            ->size(["width" => 400, "height" => 200])
            ->labels($reservasPorEstilista->pluck('nombre')->toArray())
            ->datasets([
                [
                    'label' => 'Total de reservas',
                    'data' => $reservasPorEstilista->pluck('total_reservas')->toArray(),
                    'backgroundColor' => '#4F46E5',
                    'borderRadius' => 6
                ]
            ])
            ->options([
                'scales' => [
                    'y' => [
                        'beginAtZero' => true
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
                        'text' => 'Reservas por estilista',
                        'font' => [
                            'size' => 16
                        ]
                    ]
                ]
            ]);
        
        // Formatear fechas para el gráfico de evolución
        $fechasFormateadas = $reservasPorDia->map(function($item) {
            return Carbon::parse($item->fecha)->format('d/m');
        })->toArray();
        
        // Evolución de Reservas - Gráfico de Línea
        $evolucionReservasChart = Chartjs::build()
            ->name("evolucionReservasChart")
            ->type("line")
            ->size(["width" => 400, "height" => 200])
            ->labels($fechasFormateadas)
            ->datasets([
                [
                    'label' => 'Total diario',
                    'data' => $reservasPorDia->pluck('total')->toArray(),
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
                        'beginAtZero' => true
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
                        'text' => 'Evolución de reservas por día',
                        'font' => [
                            'size' => 16
                        ]
                    ]
                ]
            ]);
        
        // Servicios - Gráfico de Donut
        $reservasPorServicioChart = Chartjs::build()
            ->name("reservasPorServicioChart")
            ->type("doughnut")
            ->size(["width" => 400, "height" => 200])
            ->labels($reservasPorServicio->pluck('nombre')->toArray())
            ->datasets([
                [
                    'data' => $reservasPorServicio->pluck('cantidad')->toArray(),
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
                        'text' => 'Distribución por servicio',
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
                                return `${val} (${percentage}%)`;
                            }'
                        ]
                    ]
                ]
            ]);
        
        return view('reservas.estadisticas-rango', compact(
            'fechaInicio',
            'fechaFin',
            'totalReservas',
            'reservasPorEstado',
            'reservasPorEstilista',
            'topEstilistas',
            'reservasPorDia',
            'reservasPorServicio',
            'reservasPorCliente',
            'tasaCancelacion',
            'tiempoMedioAntelacion',
            'reservasPorEstadoChart',
            'reservasPorEstilistaChart',
            'evolucionReservasChart',
            'reservasPorServicioChart'
        ));
    }
}

