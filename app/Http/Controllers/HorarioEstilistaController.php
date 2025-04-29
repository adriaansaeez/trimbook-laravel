<?php

namespace App\Http\Controllers;

use App\Models\Estilista;
use App\Models\Horario;
use App\Models\HorariosEstilista;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class HorarioEstilistaController extends Controller
{
    public function index()
    {
        $estilistas = Estilista::all();
        $horarios = Horario::all();
        return view('asignar_horario.index', compact('estilistas', 'horarios'));
    }

    public function obtenerHorariosEstilista($estilistaId)
    {
        try {
            Log::info('Obteniendo horarios para estilista ID: ' . $estilistaId);

            $estilista = Estilista::with(['horarios' => function($query) {
                $query->select('horarios.*')
                      ->selectRaw('horarios_estilista.id as pivot_id')
                      ->selectRaw('horarios_estilista.fecha_inicio as pivot_fecha_inicio')
                      ->selectRaw('horarios_estilista.fecha_fin as pivot_fecha_fin');
            }])->findOrFail($estilistaId);

            $horarios = $estilista->horarios->map(function ($horario) {
                // Formatear las fechas usando Carbon
                $fechaInicio = Carbon::parse($horario->pivot_fecha_inicio)->format('Y-m-d');
                $fechaFin = Carbon::parse($horario->pivot_fecha_fin)->format('Y-m-d');

                Log::info('Fechas procesadas:', [
                    'fecha_inicio_original' => $horario->pivot_fecha_inicio,
                    'fecha_fin_original' => $horario->pivot_fecha_fin,
                    'fecha_inicio_formateada' => $fechaInicio,
                    'fecha_fin_formateada' => $fechaFin
                ]);

                return [
                    'id' => $horario->id,
                    'nombre' => $horario->nombre,
                    'pivot' => [
                        'id' => $horario->pivot_id,
                        'fecha_inicio' => $fechaInicio,
                        'fecha_fin' => $fechaFin
                    ]
                ];
            });

            Log::info('Horarios encontrados: ' . count($horarios));

            return response()->json([
                'success' => true,
                'horarios' => $horarios
            ]);
        } catch (\Exception $e) {
            Log::error('Error al obtener horarios: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los horarios: ' . $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'estilista_id' => 'required|exists:estilistas,id',
                'horario_id' => 'required|exists:horarios,id',
                'fecha_inicio' => 'required|date',
                'fecha_fin' => 'required|date|after_or_equal:fecha_inicio'
            ]);

            $asignacion = HorariosEstilista::create([
                'estilista_id' => $validated['estilista_id'],
                'horario_id' => $validated['horario_id'],
                'fecha_inicio' => Carbon::parse($validated['fecha_inicio'])->format('Y-m-d'),
                'fecha_fin' => Carbon::parse($validated['fecha_fin'])->format('Y-m-d')
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Horario asignado correctamente',
                'data' => $asignacion
            ]);
        } catch (\Exception $e) {
            Log::error('Error al asignar horario: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al asignar el horario: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $horarioEstilista = HorariosEstilista::findOrFail($id);
            $horarioEstilista->delete();

            return response()->json([
                'success' => true,
                'message' => 'Asignación eliminada correctamente'
            ]);
        } catch (\Exception $e) {
            Log::error('Error al eliminar asignación: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la asignación: ' . $e->getMessage()
            ], 500);
        }
    }
} 