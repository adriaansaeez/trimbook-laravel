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


        //BOT PARA ENVIAR WASAPS
        $phone = optional($reserva->user->perfil)->telefono;

        if ($phone) {
            Http::post(config('services.whatsapp_bot.url') . '/send', [
                'phone'   => $phone,
                'message' => "✅ Reserva confirmada para {$reserva->servicio->nombre} el {$reserva->fecha} a las {$reserva->hora}."
            ]);
        } else {
            \Log::warning("El usuario {$reserva->user->id} no tiene teléfono en su perfil.");
        }

        // Enviar mensaje WhatsApp al microservicio Node
        $response = Http::post(config('services.whatsapp_bot.url') . '/send', [
            'phone'   => $phone,
            'message' => "✅ Reserva confirmada..."
        ]);
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
        $dia = strtoupper(Carbon::parse($fecha)->locale('es')->dayName);
        $servicio = Servicio::findOrFail($servicio_id);
        $dur = $servicio->duracion;
        $horarios = collect();

        // Itera todos los horarios asignados al estilista
        foreach (Estilista::findOrFail($estilista_id)->horarios as $h) {
            foreach ($h->horario as $bloque) {
                if (strtoupper($bloque['dia']) !== $dia) {
                    continue;
                }
                // Itera cada intervalo de tiempo y genera slots según duración
                foreach ($bloque['intervalos'] as $int) {
                    $start = Carbon::createFromFormat('H:i', $int['start']);
                    $end   = Carbon::createFromFormat('H:i', $int['end']);

                    while ($start->lt($end)) {
                        $slot = $start->format('H:i');

                        // Añade slot si no existe reserva en esa fecha/hora
                        if (! Reserva::where('estilista_id', $estilista_id)
                            ->where('fecha', $fecha)
                            ->where('hora', $slot)
                            ->exists()
                        ) {
                            $horarios->push($slot);
                        }

                        $start->addMinutes($dur);
                    }
                }
            }
        }

        return response()->json($horarios->unique()->values());
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

}
