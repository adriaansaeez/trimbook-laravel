<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reserva;
use App\Models\Servicio;
use App\Models\Estilista;
use Illuminate\Http\Request;
use App\Http\Resources\ReservaResource;
use Carbon\Carbon;

class ReservaController extends Controller
{
    public function index()
    {
        return ReservaResource::collection(
            Reserva::with(['servicio','estilista'])->where('user_id', auth()->id())->paginate(15)
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'servicio_id'  => 'required|exists:servicios,id',
            'estilista_id' => 'required|exists:estilistas,id',
            'fecha'        => 'required|date|after_or_equal:today',
            'hora'         => 'required|date_format:H:i',
        ]);

        if (Reserva::where('estilista_id', $data['estilista_id'])
            ->where('fecha', $data['fecha'])
            ->where('hora', $data['hora'])
            ->exists()
        ) {
            return response()->json(['message'=>'Hora ya reservada'], 422);
        }

        $reserva = Reserva::create(array_merge($data, ['user_id'=>auth()->id()]));
        return new ReservaResource($reserva);
    }

    public function show(Reserva $reserva)
    {
        $this->authorize('view', $reserva);
        return new ReservaResource($reserva->load(['servicio','estilista']));
    }

    public function destroy(Reserva $reserva)
    {
        $this->authorize('delete', $reserva);
        $reserva->delete();
        return response()->noContent();
    }

    public function getEstilistas($servicio_id)
    {
        $estilistas = Estilista::whereHas('servicios', fn($q)=> $q->where('servicios.id', $servicio_id))->get();
        return response()->json($estilistas);
    }

    public function getHorarios($estilista_id, $fecha, $servicio_id)
    {
        $dia = strtoupper(Carbon::parse($fecha)->locale('es')->dayName);
        $servicio = Servicio::findOrFail($servicio_id);
        $dur = $servicio->duracion;
        $horarios = collect();

        foreach (Estilista::findOrFail($estilista_id)->horarios as $h) {
            foreach ($h->horario as $bloque) {
                if (strtoupper($bloque['dia']) !== $dia) continue;
                foreach ($bloque['intervalos'] as $int) {
                    $start = Carbon::createFromFormat('H:i', $int['start']);
                    $end   = Carbon::createFromFormat('H:i', $int['end']);
                    while ($start->lt($end)) {
                        $slot = $start->format('H:i');
                        if (!Reserva::where('estilista_id',$estilista_id)
                            ->where('fecha',$fecha)
                            ->where('hora',$slot)
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
}
