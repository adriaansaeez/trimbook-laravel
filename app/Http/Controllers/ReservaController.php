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

        if ($user->id !== $reserva->user_id && !$user->hasRole('admin')) {
            return redirect()->back()->with('error', 'No autorizado');
        }

        $reserva->estado = 'CANCELADA';
        $reserva->save();

        return redirect()->back()->with('success', 'Reserva cancelada con éxito');
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
        
        // Verificar que el usuario es el estilista asignado a la reserva
        if (!$user->estilista || $user->estilista->id !== $reserva->estilista_id) {
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
                'estilista_id' => $user->estilista->id,
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
        
        // Verificar que el usuario es el estilista asignado a la reserva
        if (!$user->estilista || $user->estilista->id !== $reserva->estilista_id) {
            return redirect()->back()->with('error', 'No autorizado para registrar el pago de esta reserva');
        }
        
        $request->validate([
            'metodo_pago' => 'required|in:EFECTIVO,TARJETA,BIZUM,TRANSFERENCIA',
            'importe' => 'required|numeric|min:0',
        ]);
        
        // Crear el pago
        Pago::create([
            'reserva_id' => $reserva->id,
            'estilista_id' => $user->estilista->id,
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
}

