<?php

namespace App\Http\Controllers;

use App\Models\Estilista;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Models\Horario;
use Illuminate\Support\Facades\Log;

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
            $horarios = Horario::all();

            return view('estilistas.edit-horarios', compact('estilista', 'horarios'));
        } catch (\Exception $e) {
            Log::error('Error al cargar edición de horarios: ' . $e->getMessage());
            return redirect()->back()->withErrors('No se pudo cargar la edición de horarios.');
        }
    }

    public function updateHorarios(Request $request, $id)
    {
        try {
            $estilista = Estilista::findOrFail($id);

            $syncData = [];

            foreach ($request->input('horarios', []) as $dia => $horarioIds) {
                foreach ($horarioIds as $horarioId) {
                    $syncData[$horarioId] = [
                        'fecha_inicio' => $request->input("fechas.$dia.inicio"),
                        'fecha_fin' => $request->input("fechas.$dia.fin"),
                    ];
                }
            }

            $estilista->horarios()->sync($syncData);

            return redirect()->route('estilistas.edit', $estilista)
                ->with('success', 'Horarios actualizados correctamente.');

        } catch (\Exception $e) {
            \Log::error('Error al actualizar horarios del estilista: ' . $e->getMessage());
            return redirect()->back()->withErrors('Error al actualizar los horarios.');
        }
    }

}
