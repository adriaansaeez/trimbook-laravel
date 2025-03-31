<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reserva;
use App\Models\Servicio;
use App\Models\Estilista;
use App\Models\Horario;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;


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
            'servicio_id'  => 'required|exists:servicios,id',
            'estilista_id' => 'required|exists:estilistas,id',
            'fecha'        => 'required|date|after_or_equal:today',
            'hora'         => [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    if (Reserva::where('estilista_id', $request->estilista_id)
                        ->where('fecha', $request->fecha)
                        ->where('hora', $value)
                        ->exists()
                    ) {
                        $fail('La hora seleccionada ya está reservada.');
                    }
                },
            ],
        ]);

        // Crear reserva en BD
        $reserva = Reserva::create([
            'user_id'      => auth()->id(),
            'servicio_id'  => $request->servicio_id,
            'estilista_id' => $request->estilista_id,
            'fecha'        => $request->fecha,
            'hora'         => $request->hora,
        ]);

        // Obtener teléfono desde perfil
        $phone = optional($reserva->user->perfil)->telefono;

        if ($phone) {
            // Llamada al microservicio Node.js
            $response = Http::post(config('services.whatsapp_bot.url') . '/send', [
                'phone'   => $phone,
                'message' => "✅ Reserva confirmada para {$reserva->servicio->nombre} el {$reserva->fecha} a las {$reserva->hora}.",
            ]);

            // Registrar resultado en logs
            \Log::info('WhatsApp bot response', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);
        } else {
            \Log::warning("El usuario {$reserva->user->id} no tiene teléfono en su perfil.");
        }

        return redirect()->route('reservas.index')
                        ->with('success', 'Reserva creada correctamente y notificación enviada.');
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


    


}

