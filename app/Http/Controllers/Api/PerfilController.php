<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Perfil;
use Illuminate\Http\Request;
use App\Http\Resources\PerfilResource;

/**
 * Controlador API para gestionar perfiles de usuario.
 * Proporciona endpoints CRUD para listar, crear, ver, actualizar y eliminar perfiles.
 */
class PerfilController extends Controller
{
    /**
     * Lista todos los perfiles paginados.
     *
     * GET /api/v1/perfiles
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return PerfilResource::collection(Perfil::paginate(15));
    }

    /**
     * Crea un nuevo perfil asociado a un usuario existente.
     *
     * POST /api/v1/perfiles
     *
     * @param  Request  $request
     * @return PerfilResource
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'usuario_id'    => 'required|exists:users,id|unique:perfiles,usuario_id',
            'nombre'        => 'nullable|string|max:255',
            'apellidos'     => 'nullable|string|max:255',
            'telefono'      => 'nullable|string|max:20',
            'direccion'     => 'nullable|string|max:255',
            'foto_perfil'   => 'nullable|string|max:255',
            'instagram_url' => 'nullable|url|max:255',
        ]);

        $perfil = Perfil::create($data);

        return new PerfilResource($perfil);
    }

    /**
     * Muestra un perfil específico.
     *
     * GET /api/v1/perfiles/{perfil}
     *
     * @param  Perfil  $perfil
     * @return PerfilResource
     */
    public function show(Perfil $perfil)
    {
        return new PerfilResource($perfil);
    }

    /**
     * Actualiza los datos de un perfil existente.
     *
     * PUT|PATCH /api/v1/perfiles/{perfil}
     *
     * @param  Request  $request
     * @param  Perfil   $perfil
     * @return PerfilResource
     */
    public function update(Request $request, Perfil $perfil)
    {
        $data = $request->validate([
            'nombre'        => 'sometimes|string|max:255',
            'apellidos'     => 'sometimes|string|max:255',
            'telefono'      => 'sometimes|string|max:20',
            'direccion'     => 'sometimes|string|max:255',
            'foto_perfil'   => 'sometimes|string|max:255',
            'instagram_url' => 'sometimes|url|max:255',
        ]);

        $perfil->update($data);

        return new PerfilResource($perfil);
    }

    /**
     * Elimina un perfil existente.
     *
     * DELETE /api/v1/perfiles/{perfil}
     *
     * @param  Perfil  $perfil
     * @return \Illuminate\Http\Response
     */
    public function destroy(Perfil $perfil)
    {
        $perfil->delete();

        return response()->noContent();
    }
}
