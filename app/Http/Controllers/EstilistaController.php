<?php

namespace App\Http\Controllers;

use App\Models\Estilista;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Models\Horario;


class EstilistaController extends Controller
{
    // Listar estilistas
    public function index()
    {
        $estilistas = Estilista::with('user')->get();
        return view('estilistas.index', compact('estilistas'));
    }

    // Formulario de creación
    public function create()
    {
        $usuarios = User::doesntHave('estilista')->get(); // Solo usuarios que no sean estilistas
        return view('estilistas.create', compact('usuarios'));
    }

    // Guardar estilista
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id|unique:estilistas,user_id',
            'nombre' => 'required|string|max:255',
        ]);

        // Crear el estilista
        $estilista = Estilista::create($request->all());

        // Obtener el usuario y asignarle el rol 'estilista'
        $user = User::findOrFail($request->user_id);
        $user->assignRole('estilista');

        return redirect()->route('estilistas.index')->with('success', 'Estilista creado y rol asignado correctamente.');
    }

    public function edit(Estilista $estilista)
    {
        $horarios = Horario::all();
        return view('estilistas.edit', compact('estilista', 'horarios'));
    }

    public function update(Request $request, Estilista $estilista)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'horarios' => 'array',
        ]);

        $estilista->update(['nombre' => $request->nombre]);

        // Sincronizar horarios seleccionados
        $estilista->horarios()->sync($request->horarios ?? []);

        return redirect()->route('estilistas.index')->with('success', 'Estilista actualizado correctamente.');
    }


    // Eliminar estilista
    public function destroy($id)
    {
        $estilista = Estilista::findOrFail($id);

        // Obtener el usuario relacionado
        $user = $estilista->user;

        // Eliminar estilista
        $estilista->delete();

        // Remover el rol estilista si lo tiene
        if ($user->hasRole('estilista')) {
            $user->removeRole('estilista');
        }

        return redirect()->route('estilistas.index')->with('success', 'Estilista eliminado y rol removido correctamente.');
    }

}
