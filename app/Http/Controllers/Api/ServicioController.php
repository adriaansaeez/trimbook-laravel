<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Servicio;
use Illuminate\Http\Request;
use App\Http\Resources\ServicioResource;

class ServicioController extends Controller
{
    public function index()
    {
        return ServicioResource::collection(Servicio::with('estilistas')->paginate(15));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'      => 'required|string|max:255',
            'descripcion' => 'required|string',
            'precio'      => 'required|numeric|min:0',
            'duracion'    => 'required|integer|min:1',
        ]);
        $servicio = Servicio::create($data);
        $servicio->estilistas()->sync($request->input('estilistas', []));


        return new ServicioResource($servicio);
    }

    public function show(Servicio $servicio)
    {
        return new ServicioResource($servicio->load('estilistas'));
    }

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

    public function destroy(Servicio $servicio)
    {
        $servicio->delete();
        return response()->noContent();
    }
}
