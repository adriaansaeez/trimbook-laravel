<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Estilista;
use Illuminate\Http\Request;
use App\Http\Resources\EstilistaResource;


class EstilistaController extends Controller
{
    public function index()
    {
        return EstilistaResource::collection(
            Estilista::with(['user','servicios','horarios'])->paginate(15)
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id|unique:estilistas,user_id',
            'nombre'  => 'required|string|max:255',
        ]);

        $estilista = Estilista::create($data);
        $estilista->servicios()->sync($request->input('servicios', []));
        $estilista->user->assignRole('estilista');

        return new EstilistaResource($estilista);
    }

    public function show(Estilista $estilista)
    {
        return new EstilistaResource($estilista->load(['user','servicios','horarios']));
    }

    public function update(Request $request, Estilista $estilista)
    {
        $data = $request->validate([
            'nombre' => 'sometimes|string|max:255',
        ]);

        $estilista->update($data);

        return new EstilistaResource($estilista);
    }

    public function destroy(Estilista $estilista)
    {
        $estilista->user->removeRole('estilista');
        $estilista->delete();

        return response()->noContent();
    }
}
