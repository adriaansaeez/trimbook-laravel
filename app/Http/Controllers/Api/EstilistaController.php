<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Estilista;
use Illuminate\Http\Request;
use App\Http\Resources\EstilistaResource;

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
}
