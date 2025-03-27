<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Perfil;
use Illuminate\Http\Request;
use App\Http\Resources\PerfilResource;

class PerfilController extends Controller
{
    public function index()
    {
        return PerfilResource::collection(Perfil::paginate(15));
    }

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

    public function show(Perfil $perfil)
    {
        return new PerfilResource($perfil);
    }

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

    public function destroy(Perfil $perfil)
    {
        $perfil->delete();
        return response()->noContent();
    }
}
