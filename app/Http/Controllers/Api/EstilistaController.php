<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Estilista;
use App\Models\HorariosEstilista;
use Illuminate\Http\Request;
use App\Http\Resources\EstilistaResource;
use Carbon\Carbon;

/**
 * Controlador API para gestionar estilistas.
 * Proporciona endpoints CRUD para listar, crear, ver, actualizar y eliminar estilistas.
 */
class EstilistaController extends Controller
{
    /**
     * Lista todos los estilistas paginados junto con sus relaciones (usuario, servicios, horarios).
     *
     * GET /api/v1/estilistas
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return EstilistaResource::collection(
            Estilista::with(['user','servicios','horarios'])->paginate(15)
        );
    }

    /**
     * Crea un nuevo estilista asociado a un usuario existente y asigna el rol 'estilista'.
     *
     * POST /api/v1/estilistas
     *
     * @param  Request  $request
     * @return EstilistaResource
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id|unique:estilistas,user_id',
            'nombre'  => 'required|string|max:255',
        ]);

        $estilista = Estilista::create($data);

        // Sincroniza servicios opcionales (array de IDs)
        $estilista->servicios()->sync($request->input('servicios', []));

        // Asigna el rol de estilista al usuario asociado
        $estilista->user->assignRole('estilista');

        return new EstilistaResource($estilista);
    }

    /**
     * Muestra los detalles de un estilista específico, cargando relaciones.
     *
     * GET /api/v1/estilistas/{estilista}
     *
     * @param  Estilista  $estilista
     * @return EstilistaResource
     */
    public function show(Estilista $estilista)
    {
        return new EstilistaResource(
            $estilista->load(['user','servicios','horarios'])
        );
    }

    /**
     * Actualiza los datos de un estilista existente.
     *
     * PUT|PATCH /api/v1/estilistas/{estilista}
     *
     * @param  Request    $request
     * @param  Estilista  $estilista
     * @return EstilistaResource
     */
    public function update(Request $request, Estilista $estilista)
    {
        $data = $request->validate([
            'nombre' => 'sometimes|string|max:255',
        ]);

        $estilista->update($data);

        return new EstilistaResource($estilista);
    }

    /**
     * Elimina un estilista y remueve su rol de usuario.
     *
     * DELETE /api/v1/estilistas/{estilista}
     *
     * @param  Estilista  $estilista
     * @return \Illuminate\Http\Response
     */
    public function destroy(Estilista $estilista)
    {
        // Remueve el rol 'estilista' del usuario relacionado
        $estilista->user->removeRole('estilista');

        // Elimina el registro de estilista
        $estilista->delete();

        return response()->noContent();
    }

    /**
     * Asigna servicios a un estilista.
     *
     * POST /api/v1/estilistas/{estilista}/servicios
     *
     * @param  Request    $request
     * @param  Estilista  $estilista
     * @return \Illuminate\Http\JsonResponse
     */
    public function asignarServicios(Request $request, Estilista $estilista)
    {
        $request->validate([
            'servicios' => 'required|array',
            'servicios.*' => 'exists:servicios,id'
        ]);

        $estilista->servicios()->sync($request->servicios);

        return response()->json([
            'message' => 'Servicios asignados correctamente',
            'estilista' => new EstilistaResource($estilista->load('servicios'))
        ]);
    }

    /**
     * Asigna horarios a un estilista.
     *
     * POST /api/v1/estilistas/{estilista}/horarios
     *
     * @param  Request    $request
     * @param  Estilista  $estilista
     * @return \Illuminate\Http\JsonResponse
     */
    public function asignarHorario(Request $request, Estilista $estilista)
    {
        $request->validate([
            'horario' => 'required|array',
            'horario.*.dia' => 'required|string|in:LUNES,MARTES,MIERCOLES,JUEVES,VIERNES,SABADO,DOMINGO',
            'horario.*.intervalos' => 'required|array',
            'horario.*.intervalos.*.start' => 'required|date_format:H:i',
            'horario.*.intervalos.*.end' => 'required|date_format:H:i|after:horario.*.intervalos.*.start',
            'fecha_inicio' => 'required|date|after_or_equal:today',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio'
        ]);

        $horarioEstilista = $estilista->horarios()->create([
            'horario' => $request->horario,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin
        ]);

        return response()->json([
            'message' => 'Horario asignado correctamente',
            'horario' => $horarioEstilista,
            'estilista' => new EstilistaResource($estilista->load('horarios'))
        ]);
    }

    /**
     * Elimina un horario específico de un estilista.
     *
     * DELETE /api/v1/estilistas/{estilista}/horarios/{horario}
     *
     * @param  Estilista  $estilista
     * @param  int        $horarioId
     * @return \Illuminate\Http\Response
     */
    public function eliminarHorario(Estilista $estilista, $horarioId)
    {
        $horario = $estilista->horarios()->findOrFail($horarioId);
        $horario->delete();

        return response()->noContent();
    }
}
