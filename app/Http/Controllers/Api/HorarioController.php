<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Horario;
use Illuminate\Http\Request;
use App\Http\Resources\HorarioResource;

/**
 * Controlador API para gestionar Horarios.
 * Proporciona endpoints CRUD para listar, crear, ver, actualizar y eliminar horarios.
 */
class HorarioController extends Controller
{
    /**
     * Lista todos los horarios paginados junto con los estilistas asociados.
     *
     * GET /api/v1/horarios
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return HorarioResource::collection(
            Horario::with('estilistas')->paginate(15)
        );
    }

    /**
     * Crea un nuevo horario.
     *
     * POST /api/v1/horarios
     *
     * @param  Request  $request
     * @return HorarioResource
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'  => 'required|string|max:255',
            'horario' => 'required|array',
        ]);

        $horario = Horario::create($data);

        return new HorarioResource($horario);
    }

    /**
     * Muestra un horario específico junto con sus estilistas.
     *
     * GET /api/v1/horarios/{horario}
     *
     * @param  Horario  $horario
     * @return HorarioResource
     */
    public function show(Horario $horario)
    {
        return new HorarioResource($horario->load('estilistas'));
    }

    /**
     * Actualiza los datos de un horario existente.
     *
     * PUT|PATCH /api/v1/horarios/{horario}
     *
     * @param  Request  $request
     * @param  Horario  $horario
     * @return HorarioResource
     */
    public function update(Request $request, Horario $horario)
    {
        $data = $request->validate([
            'nombre'  => 'sometimes|string|max:255',
            'horario' => 'sometimes|array',
        ]);

        $horario->update($data);

        return new HorarioResource($horario);
    }

    /**
     * Elimina un horario.
     *
     * DELETE /api/v1/horarios/{horario}
     *
     * @param  Horario  $horario
     * @return \Illuminate\Http\Response
     */
    public function destroy(Horario $horario)
    {
        $horario->delete();

        return response()->noContent();
    }
}
