<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Servicio;
use Illuminate\Http\Request;
use App\Http\Resources\ServicioResource;

/**
 * Controlador API para gestionar servicios.
 * Proporciona endpoints CRUD para listar, crear, ver, actualizar y eliminar servicios.
 */
class ServicioController extends Controller
{
    /**
     * Lista todos los servicios paginados junto con sus estilistas asociados.
     *
     * GET /api/v1/servicios
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return ServicioResource::collection(
            Servicio::with('estilistas')->paginate(15)
        );
    }

    /**
     * Crea un nuevo servicio y sincroniza cualquier estilista asignado.
     *
     * POST /api/v1/servicios
     *
     * @param  Request  $request
     * @return ServicioResource
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'      => 'required|string|max:255',
            'descripcion' => 'required|string',
            'precio'      => 'required|numeric|min:0',
            'duracion'    => 'required|integer|min:1',
        ]);

        $servicio = Servicio::create($data);

        // Sincroniza la relación muchos-a-muchos con estilistas si se proporcionan IDs
        $servicio->estilistas()->sync($request->input('estilistas', []));

        return new ServicioResource($servicio);
    }

    /**
     * Muestra un servicio específico junto con sus estilistas asociados.
     *
     * GET /api/v1/servicios/{servicio}
     *
     * @param  Servicio  $servicio
     * @return ServicioResource
     */
    public function show(Servicio $servicio)
    {
        return new ServicioResource($servicio->load('estilistas'));
    }

    /**
     * Actualiza los campos de un servicio existente.
     *
     * PUT|PATCH /api/v1/servicios/{servicio}
     *
     * @param  Request   $request
     * @param  Servicio  $servicio
     * @return ServicioResource
     */
    public function update(Request $request, Servicio $servicio)
    {
        $data = $request->validate([
            'nombre'      => 'sometimes|string|max:255',
            'descripcion' => 'sometimes|string',
            'precio'      => 'sometimes|numeric|min:0',
            'duracion'    => 'sometimes|integer|min:1',
        ]);

        $servicio->update($data);

        return new ServicioResource($servicio);
    }

    /**
     * Elimina un servicio existente.
     *
     * DELETE /api/v1/servicios/{servicio}
     *
     * @param  Servicio  $servicio
     * @return \Illuminate\Http\Response
     */
    public function destroy(Servicio $servicio)
    {
        $servicio->delete();

        return response()->noContent();
    }
}
