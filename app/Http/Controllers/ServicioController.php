<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use App\Models\Estilista;
use Illuminate\Http\Request;

class ServicioController extends Controller
{
    // Mostrar todos los servicios
    public function index()
    {
        $servicios = Servicio::with('estilistas')->paginate(10);
        return view('servicios.index', compact('servicios'));
    }

    // Formulario de creación
    public function create()
    {
        return view('servicios.create');
    }

    // Guardar un nuevo servicio
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'precio' => 'required|numeric|min:0',
            'duracion' => 'required|integer|min:1',
        ]);

        $servicio = Servicio::create($request->all());

        return redirect()->route('servicios.index')->with('success', 'Servicio creado correctamente.');
    }

    // Formulario de edición con selección de estilistas
    public function edit(Servicio $servicio)
    {
        $estilistas = Estilista::all();
        return view('servicios.edit', compact('servicio', 'estilistas'));
    }

    // Actualizar servicio y asignar estilistas
    public function update(Request $request, Servicio $servicio)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'precio' => 'required|numeric|min:0',
            'duracion' => 'required|integer|min:1',
            'estilistas' => 'array',
            'estilistas.*' => 'exists:estilistas,id',
        ]);

        $servicio->update($request->only(['nombre', 'descripcion', 'precio', 'duracion']));
        $servicio->estilistas()->sync($request->estilistas ?? []); // Sincroniza estilistas seleccionados

        return redirect()->route('servicios.index')->with('success', 'Servicio actualizado correctamente.');
    }

    // Eliminar servicio
    public function destroy(Servicio $servicio)
    {
        $servicio->delete();
        return redirect()->route('servicios.index')->with('success', 'Servicio eliminado correctamente.');
    }
}

