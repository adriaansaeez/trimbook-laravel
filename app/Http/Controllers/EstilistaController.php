<?php

namespace App\Http\Controllers;

use App\Models\Estilista;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Models\Horario;
use Illuminate\Support\Facades\Log;
use App\Models\Servicio;

class EstilistaController extends Controller
{
    public function index()
    {
        try {
            $estilistas = Estilista::with('user')->get();
            return view('estilistas.index', compact('estilistas'));
        } catch (\Exception $e) {
            Log::error('Error al listar estilistas: ' . $e->getMessage());
            return redirect()->back()->withErrors('No se pudo cargar la lista de estilistas.');
        }
    }

    public function create()
    {
        try {
            $usuarios = User::doesntHave('estilista')->get();
            return view('estilistas.create', compact('usuarios'));
        } catch (\Exception $e) {
            Log::error('Error al cargar el formulario de creación: ' . $e->getMessage());
            return redirect()->back()->withErrors('No se pudo cargar el formulario.');
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|exists:users,id|unique:estilistas,user_id',
                'nombre' => 'required|string|max:255',
            ]);

            $estilista = Estilista::create($request->all());
            $user = User::findOrFail($request->user_id);
            $user->assignRole('estilista');

            return redirect()->route('estilistas.index')->with('success', 'Estilista creado y rol asignado correctamente.');
        } catch (\Exception $e) {
            Log::error('Error al guardar estilista: ' . $e->getMessage());
            return redirect()->back()->withErrors('Error al crear el estilista.');
        }
    }

    public function edit(Estilista $estilista)
    {
        try {
            $horarios = Horario::all();
            return view('estilistas.edit', compact('estilista', 'horarios'));
        } catch (\Exception $e) {
            Log::error('Error al cargar el formulario de edición: ' . $e->getMessage());
            return redirect()->back()->withErrors('No se pudo cargar la edición del estilista.');
        }
    }

    public function update(Request $request, Estilista $estilista)
    {
        try {
            $request->validate([
                'nombre' => 'required|string|max:255',
                'horarios' => 'array',
            ]);

            $estilista->update(['nombre' => $request->nombre]);
            $estilista->horarios()->sync($request->horarios ?? []);

            return redirect()->route('estilistas.index')->with('success', 'Estilista actualizado correctamente.');
        } catch (\Exception $e) {
            Log::error('Error al actualizar estilista: ' . $e->getMessage());
            return redirect()->back()->withErrors('Error al actualizar el estilista.');
        }
    }

    public function destroy($id)
    {
        try {
            $estilista = Estilista::findOrFail($id);
            $user = $estilista->user;

            $estilista->delete();

            if ($user->hasRole('estilista')) {
                $user->removeRole('estilista');
            }

            return redirect()->route('estilistas.index')->with('success', 'Estilista eliminado y rol removido correctamente.');
        } catch (\Exception $e) {
            Log::error('Error al eliminar estilista: ' . $e->getMessage());
            return redirect()->back()->withErrors('Error al eliminar el estilista.');
        }
    }

    public function editHorarios($id)
    {
        try {
            $estilista = Estilista::with('horarios')->findOrFail($id);
            $allHorarios = Horario::all();

            return view('estilistas.edit-horarios', compact('estilista', 'allHorarios'));
        } catch (\Exception $e) {
            Log::error('Error al cargar edición de horarios: ' . $e->getMessage());
            return redirect()->back()->withErrors('No se pudo cargar la edición de horarios.');
        }
    }

    public function vistaAsignarHorarioIndex(Request $request)
    {
        $estilistas = Estilista::all();
        $estilistaSeleccionado = null;
        $horarios = [];

        if ($request->has('estilista_id')) {
            $estilistaSeleccionado = Estilista::find($request->estilista_id);
            $horarios = $estilistaSeleccionado->horarios; // Asumiendo relación horarios()
        }

        return view('asignar_horario.index', compact('estilistas', 'estilistaSeleccionado', 'horarios'));
    }

    // Muestra el formulario para asignar horarios
    public function vistaAsignarHorarioForm($id)
    {
        $estilista = Estilista::findOrFail($id);
        $horariosDisponibles = Horario::all(); // o filtra según lógica de tu app

        return view('asignar_horario.create', compact('estilista', 'horariosDisponibles'));
    }

    // Guarda la asignación
    public function guardarAsignacionHorario(Request $request, $id)
    {
        $request->validate([
            'horario_id' => 'required|exists:horarios,id',
            'fecha_inicio' => 'required|date|before_or_equal:fecha_fin',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ]);

        $estilista = Estilista::findOrFail($id);

        // Evita duplicados del mismo horario y rango
        $yaExiste = $estilista->horarios()
            ->wherePivot('horario_id', $request->horario_id)
            ->wherePivot('fecha_inicio', $request->fecha_inicio)
            ->wherePivot('fecha_fin', $request->fecha_fin)
            ->exists();

        if ($yaExiste) {
            return back()->with('error', 'Este horario ya ha sido asignado con ese rango de fechas.');
        }

        // Asignar horario con fechas
        $estilista->horarios()->attach($request->horario_id, [
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
        ]);

        return redirect()->route('asignar_horario.index', ['estilista_id' => $estilista->id])
            ->with('success', 'Horario asignado correctamente.');
    }
    public function formAsignarServicios()
    {
        $estilistas = Estilista::all();
        $servicios = Servicio::all();

        return view('estilistas.asignar-servicios', compact('estilistas', 'servicios'));
    }

    public function asignarServicios(Request $request)
    {
        $request->validate([
            'estilista_id' => 'required|exists:estilistas,id',
            'servicios' => 'array',
            'servicios.*' => 'exists:servicios,id',
        ]);

        $estilista = Estilista::findOrFail($request->estilista_id);
        $estilista->servicios()->sync($request->servicios ?? []); // sincroniza los seleccionados

        return redirect()->back()->with('success', 'Servicios asignados correctamente.');
    }



}
