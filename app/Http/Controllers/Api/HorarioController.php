<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Horario;
use Illuminate\Http\Request;
use App\Http\Resources\HorarioResource;

class HorarioController extends Controller
{
    public function index()
    {
        return HorarioResource::collection(
            Horario::with('estilistas')->paginate(15)
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'horario' => 'required|array',
        ]);

        $horario = Horario::create($data);
        return new HorarioResource($horario);
    }

    public function show(Horario $horario)
    {
        return new HorarioResource($horario->load('estilistas'));
    }

    public function update(Request $request, Horario $horario)
    {
        $data = $request->validate([
            'nombre' => 'sometimes|string|max:255',
            'horario' => 'sometimes|array',
        ]);

        $horario->update($data);
        return new HorarioResource($horario);
    }

    public function destroy(Horario $horario)
    {
        $horario->delete();
        return response()->noContent();
    }
}
