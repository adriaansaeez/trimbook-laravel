<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reserva;
use App\Models\Servicio;
use App\Models\Estilista;
use App\Models\Horario;
use Carbon\Carbon;

class ReservaController extends Controller
{
    public function index()
    {
        $reservas = Reserva::where('user_id', auth()->id())->get();
        return view('reservas.index', compact('reservas'));
    }

    public function create()
    {
        $servicios = Servicio::all();
        return view('reservas.create', compact('servicios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'servicio_id' => 'required|exists:servicios,id',
            'estilista_id' => 'required|exists:estilistas,id',
            'fecha' => 'required|date',
            'hora' => 'required',
        ]);

        // Guardar reserva
        Reserva::create([
            'user_id' => auth()->id(),
            'servicio_id' => $request->servicio_id,
            'estilista_id' => $request->estilista_id,
            'fecha' => $request->fecha,
            'hora' => $request->hora,
        ]);

        return redirect()->route('reservas.index')->with('success', 'Reserva creada correctamente.');
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


    // Obtener horarios disponibles para un estilista en una fecha
    public function getHorarios($estilista_id, $fecha)
    {
        // Obtener el día de la semana en mayúsculas (ejemplo: LUNES)
        $diaSemana = strtoupper(Carbon::parse($fecha)->locale('es')->dayName);
        
        // Obtener todos los horarios asignados al estilista (relación definida a través de horarios_estilista)
        $horarios = Horario::whereHas('estilistas', function ($query) use ($estilista_id) {
            $query->where('estilistas.id', $estilista_id);
        })->get();
        
        // Se obtiene el servicio asociado al estilista para determinar la duración del turno.
        // (En este ejemplo se obtiene el primer servicio encontrado para el estilista.)
        $servicio = Servicio::whereHas('estilistas', function ($query) use ($estilista_id) {
            $query->where('estilistas.id', $estilista_id);
        })->first();
        
        if (!$servicio) {
            return response()->json([]);
        }
        
        $duracion = $servicio->duracion; // Duración en minutos
        $horasDisponibles = [];
        
        // Recorrer cada registro de horarios asociados al estilista
        foreach ($horarios as $horario) {
            // Se asume que el campo "horario" es un array con bloques de día, cada uno con un atributo "dia" e "intervalos"
            if (!is_array($horario->horario)) {
                continue;
            }
            
            // Buscar el bloque que corresponda al día de la semana solicitado
            $bloque = null;
            foreach ($horario->horario as $bloqueDia) {
                if (strtoupper($bloqueDia['dia']) == $diaSemana) {
                    $bloque = $bloqueDia;
                    break;
                }
            }
            
            // Si se encontró un bloque para el día y este tiene intervalos definidos
            if ($bloque && isset($bloque['intervalos']) && is_array($bloque['intervalos'])) {
                foreach ($bloque['intervalos'] as $intervalo) {
                    // Convertir el inicio y fin a objetos Carbon
                    $horaInicio = Carbon::createFromFormat('H:i', $intervalo['start']);
                    $horaFin = Carbon::createFromFormat('H:i', $intervalo['end']);
                    
                    // Recorrer el intervalo en saltos iguales a la duración del servicio
                    while ($horaInicio->lt($horaFin)) {
                        $horaStr = $horaInicio->format('H:i');
                        
                        // Verificar si ya existe una reserva para ese estilista, fecha y hora
                        $existeReserva = Reserva::where('estilista_id', $estilista_id)
                            ->where('fecha', $fecha)
                            ->where('hora', $horaStr)
                            ->exists();
                        
                        if (!$existeReserva) {
                            $horasDisponibles[] = $horaStr;
                        }
                        
                        $horaInicio->addMinutes($duracion);
                    }
                }
            }
        }
        
        return response()->json($horasDisponibles);
    }

}

