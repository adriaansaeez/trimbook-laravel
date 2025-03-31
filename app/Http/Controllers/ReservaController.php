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
            'fecha' => 'required|date|after_or_equal:today',
            'hora' => [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    $existe = Reserva::where('estilista_id', $request->estilista_id)
                        ->where('fecha', $request->fecha)
                        ->where('hora', $value)
                        ->exists();
                    if ($existe) {
                        $fail('La hora seleccionada ya está reservada.');
                    }
                },
            ],
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


    


}

