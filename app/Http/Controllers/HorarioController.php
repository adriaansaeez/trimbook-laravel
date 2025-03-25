<?php

namespace App\Http\Controllers;

use App\Models\Horario;
use Illuminate\Http\Request;

class HorarioController extends Controller
{
    public function index()
    {
        $horarios = Horario::all();
        return view('horarios.index', compact('horarios'));
    }

    public function create()
    {
        return view('horarios.create');
    }

    public function store(Request $request)
    {
        // Validamos que se envíe un array "horario" con la estructura requerida.
        $request->validate([
            'horario' => 'required|array',
            'horario.*.dia' => 'required|in:LUNES,MARTES,MIERCOLES,JUEVES,VIERNES,SABADO,DOMINGO',
            'horario.*.intervalos' => 'required|array',
            'horario.*.intervalos.*.start' => 'required|date_format:H:i',
            'horario.*.intervalos.*.end' => 'required|date_format:H:i',
        ]);

        // Validación adicional: Para cada intervalo, la hora de fin debe ser posterior a la hora de inicio.
        foreach ($request->input('horario') as $diaIndex => $dia) {
            foreach ($dia['intervalos'] as $intervaloIndex => $intervalo) {
                $start = $intervalo['start'];
                $end = $intervalo['end'];
                if (strtotime($end) <= strtotime($start)) {
                    return back()
                        ->withErrors([
                            "horario.$diaIndex.intervalos.$intervaloIndex.end" => 'La hora de fin debe ser posterior a la hora de inicio.'
                        ])
                        ->withInput();
                }
            }
        }

        // Al crear el registro, el modelo calculará automáticamente "registro_horas_semanales"
        Horario::create($request->all());

        return redirect()->route('horarios.index')->with('success', 'Horario creado correctamente.');
    }

    public function edit(Horario $horario)
    {
        return view('horarios.edit', compact('horario'));
    }

    public function update(Request $request, Horario $horario)
    {
        // Validamos la estructura del array "horario"
        $request->validate([
            'horario' => 'required|array',
            'horario.*.dia' => 'required|in:LUNES,MARTES,MIERCOLES,JUEVES,VIERNES,SABADO,DOMINGO',
            'horario.*.intervalos' => 'required|array',
            'horario.*.intervalos.*.start' => 'required|date_format:H:i',
            'horario.*.intervalos.*.end' => 'required|date_format:H:i',
        ]);

        // Validación adicional: La hora de fin debe ser posterior a la de inicio en cada intervalo.
        foreach ($request->input('horario') as $diaIndex => $dia) {
            foreach ($dia['intervalos'] as $intervaloIndex => $intervalo) {
                $start = $intervalo['start'];
                $end = $intervalo['end'];
                if (strtotime($end) <= strtotime($start)) {
                    return back()
                        ->withErrors([
                            "horario.$diaIndex.intervalos.$intervaloIndex.end" => 'La hora de fin debe ser posterior a la hora de inicio.'
                        ])
                        ->withInput();
                }
            }
        }

        // Actualizamos el registro; el trigger en el modelo recalculará las horas semanales
        $horario->update($request->all());

        return redirect()->route('horarios.index')->with('success', 'Horario actualizado correctamente.');
    }
    public function show(Horario $horario)
    {
        return view('horarios.show', compact('horario'));
    }


    public function destroy(Horario $horario)
    {
        $horario->delete();
        return redirect()->route('horarios.index')->with('success', 'Horario eliminado.');
    }
}
