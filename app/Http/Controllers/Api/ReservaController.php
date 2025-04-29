<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reserva;
use App\Models\Servicio;
use App\Models\Estilista;
use Illuminate\Http\Request;
use App\Http\Resources\ReservaResource;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

use App\Models\HorariosEstilista;

/**
 * Controlador API para gestionar reservas.
 * Proporciona endpoints CRUD y métodos auxiliares para obtener estilistas disponibles
 * y horarios libres basados en fecha y servicio.
 */
class ReservaController extends Controller
{
    /**
     * Lista las reservas del usuario autenticado (paginadas).
     *
     * GET /api/v1/reservas
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return ReservaResource::collection(
            Reserva::with(['servicio','estilista'])
                ->where('user_id', auth()->id())
                ->paginate(15)
        );
    }

    /**
     * Crea una nueva reserva validando disponibilidad.
     *
     * POST /api/v1/reservas
     *
     * @param  Request  $request
     * @return ReservaResource|\Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'servicio_id'  => 'required|exists:servicios,id',
            'estilista_id' => 'required|exists:estilistas,id',
            'fecha'        => 'required|date|after_or_equal:today',
            'hora'         => 'required|date_format:H:i',
        ]);

        // Verifica si ya existe reserva para esa fecha, hora y estilista
        if (Reserva::where('estilista_id', $data['estilista_id'])
            ->where('fecha', $data['fecha'])
            ->where('hora', $data['hora'])
            ->exists()
        ) {
            return response()->json(['message' => 'Hora ya reservada'], 422);
        }

        $reserva = Reserva::create(array_merge($data, ['user_id' => auth()->id()]));

        return new ReservaResource($reserva);
    }

    /**
     * Muestra los detalles de una reserva específica.
     *
     * GET /api/v1/reservas/{reserva}
     *
     * @param  Reserva  $reserva
     * @return ReservaResource
     */
    public function show(Reserva $reserva)
    {
        $this->authorize('view', $reserva);
        return new ReservaResource($reserva->load(['servicio','estilista']));
    }

    /**
     * Cancela (elimina) una reserva existente.
     *
     * DELETE /api/v1/reservas/{reserva}
     *
     * @param  Reserva  $reserva
     * @return \Illuminate\Http\Response
     */
    public function destroy(Reserva $reserva)
    {
        $this->authorize('delete', $reserva);
        $reserva->delete();
        return response()->noContent();
    }

    /**
     * Retorna una lista de estilistas que ofrecen un servicio dado.
     *
     * GET /api/v1/reservas/estilistas/{servicio_id}
     *
     * @param  int  $servicio_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getEstilistas($servicio_id)
    {
        $estilistas = Estilista::whereHas('servicios', fn($q) =>
            $q->where('servicios.id', $servicio_id)
        )->get();

        return response()->json($estilistas);
    }

    /**
     * Calcula y retorna los horarios disponibles para un estilista en una fecha específica.
     *
     * GET /api/v1/reservas/horarios/{estilista_id}/{fecha}/{servicio_id}
     *
     * @param  int     $estilista_id
     * @param  string  $fecha
     * @param  int     $servicio_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getHorarios($estilista_id, $fecha, $servicio_id)
    {
        try {
            $dia = strtoupper(Carbon::parse($fecha)->locale('es')->dayName);
            $servicio = Servicio::findOrFail($servicio_id);
            $dur = $servicio->duracion;
            $horarios = collect();

            // Obtener el estilista
            $estilista = Estilista::findOrFail($estilista_id);
            
            // Verificar si el estilista tiene horarios asignados
            if (!$estilista->horarios || $estilista->horarios->isEmpty()) {
                \Log::info("El estilista {$estilista_id} no tiene horarios asignados");
                return response()->json([]);
            }

            \Log::info("Procesando horarios para estilista {$estilista_id}, día {$dia}, fecha {$fecha}");
            
            // Itera todos los horarios asignados al estilista
            foreach ($estilista->horarios as $h) {
                $horario = is_array($h->horario) ? $h->horario : json_decode($h->horario, true);
                if (!is_array($horario)) {
                    \Log::info("Horario no es un array válido: " . json_encode($h->horario));
                    continue;
                }
            
                foreach ($horario as $bloque) {
                    if (!is_array($bloque) || !isset($bloque['dia']) || !isset($bloque['intervalos'])) {
                        \Log::info("Bloque de horario inválido: " . json_encode($bloque));
                        continue;
                    }
            
                    if (strtoupper($bloque['dia']) !== $dia) {
                        continue;
                    }
            
                    foreach ($bloque['intervalos'] as $int) {
                        if (!isset($int['start']) || !isset($int['end'])) {
                            \Log::info("Intervalo inválido: " . json_encode($int));
                            continue;
                        }
                        
                        $start = Carbon::createFromFormat('H:i', $int['start']);
                        $end = Carbon::createFromFormat('H:i', $int['end']);
            
                        while ($start->lt($end)) {
                            $slot = $start->format('H:i');
            
                            if (!Reserva::where('estilista_id', $estilista_id)
                                ->where('fecha', $fecha)
                                ->where('hora', $slot)
                                ->exists()) {
                                $horarios->push($slot);
                            }
            
                            $start->addMinutes($dur);
                        }
                    }
                }
            }
            
            $resultado = $horarios->unique()->values();
            \Log::info("Horarios disponibles encontrados: " . $resultado->count());
            
            return response()->json($resultado);
        } catch (\Exception $e) {
            \Log::error("Error en getHorarios: " . $e->getMessage());
            return response()->json(['error' => 'Error al procesar los horarios'], 500);
        }
    }
    /**
     * Retorna las fechas disponibles (próximos 30 días) en que el estilista trabaja,
     * basándose en los registros de horarios_estilista y la información en la columna 'horario'.
     *
     * GET /api/v1/horarios-estilista/dias-disponibles/{estilista_id}
     *
     * @param int $estilista_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDiasDisponibles($estilista_id)
    {
        $availableDates = [];
        $today = Carbon::today();
        $endDate = Carbon::today()->addDays(30);

        // Obtener todos los registros activos para el estilista
        $schedules = HorariosEstilista::with('horario') // asumiendo relación "horario" en el modelo
            ->where('estilista_id', $estilista_id)
            ->whereDate('fecha_inicio', '<=', $today->format('Y-m-d'))
            ->whereDate('fecha_fin', '>=', $today->format('Y-m-d'))
            ->get();

        // Si no hay horarios activos, devolver un array vacío
        if ($schedules->isEmpty()) {
            return response()->json([]);
        }

        // Recorrer cada día en el rango
        for ($date = $today->copy(); $date->lte($endDate); $date->addDay()) {
            // Obtenemos el nombre del día en mayúsculas (ej: "LUNES")
            $dayName = strtoupper($date->locale('es')->dayName);
            $works = false;

            // Revisar cada registro de horarios para ver si el día actual está permitido
            foreach ($schedules as $schedule) {
                $horario = is_array($schedule->horario) ? $schedule->horario : json_decode($schedule->horario, true);
                if (!is_array($horario)) {
                    continue;
                }
                foreach ($horario as $dayData) {
                    if (!is_array($dayData) || !isset($dayData['dia'])) {
                        continue;
                    }
                    if (strtoupper($dayData['dia']) === $dayName && isset($dayData['intervalos']) && count($dayData['intervalos']) > 0) {
                        $works = true;
                        break 2;
                    }
                }
            }

            if ($works) {
                $availableDates[] = $date->format('Y-m-d');
            }
        }

        return response()->json($availableDates);
    }

    /**
     * Cambia el estado de una reserva.
     *
     * PUT /api/v1/reservas/{reserva}/estado
     *
     * @param  Request  $request
     * @param  Reserva  $reserva
     * @return \Illuminate\Http\JsonResponse
     */
    public function cambiarEstado(Request $request, Reserva $reserva)
    {
        $request->validate([
            'estado' => 'required|in:pendiente,confirmada,completada,cancelada'
        ]);

        $this->authorize('update', $reserva);
        $reserva->update(['estado' => $request->estado]);

        return response()->json(['message' => 'Estado actualizado correctamente', 'reserva' => new ReservaResource($reserva)]);
    }

    /**
     * Registra el pago de una reserva.
     *
     * POST /api/v1/reservas/{reserva}/pago
     *
     * @param  Request  $request
     * @param  Reserva  $reserva
     * @return \Illuminate\Http\JsonResponse
     */
    public function registrarPago(Request $request, Reserva $reserva)
    {
        $request->validate([
            'monto' => 'required|numeric|min:0',
            'metodo_pago' => 'required|string'
        ]);

        $this->authorize('update', $reserva);
        
        $pago = $reserva->pagos()->create([
            'monto' => $request->monto,
            'metodo_pago' => $request->metodo_pago,
            'estado' => 'completado'
        ]);

        $reserva->update(['estado' => 'completada']);

        return response()->json([
            'message' => 'Pago registrado correctamente',
            'pago' => $pago,
            'reserva' => new ReservaResource($reserva)
        ]);
    }
}
