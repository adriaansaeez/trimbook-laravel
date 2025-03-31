<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Horario;
use Illuminate\Http\Request;
use App\Http\Resources\HorarioResource;
use Carbon\Carbon;


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

    /**
     * Retorna las fechas disponibles de los horarios del estilista para los próximos 30 días.
     *
     * GET /api/v1/reservas/dias-trabaja/{estilista_id}
     *
     * @param  int  $estilista_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDiasTrabaja($estilista_id)
    {
        $availableDates = [];
        $today = Carbon::today();
        $endDate = Carbon::today()->addDays(30);

        // Obtenemos el estilista junto con sus horarios
        $estilista = \App\Models\Estilista::with('horarios')->findOrFail($estilista_id);

        // Iteramos por cada día en el rango
        for ($date = $today->copy(); $date->lte($endDate); $date->addDay()) {
            // Obtenemos el nombre del día en mayúsculas (por ejemplo, "LUNES")
            $dayName = strtoupper($date->locale('es')->dayName);
            $dayAvailable = false;

            // Recorremos cada bloque de horario del estilista
            foreach ($estilista->horarios as $horario) {
                if (!is_array($horario->horario)) {
                    continue;
                }
                foreach ($horario->horario as $bloque) {
                    if (strtoupper($bloque['dia']) === $dayName) {
                        // Si el estilista tiene configurado algún intervalo en ese día, lo consideramos disponible.
                        $dayAvailable = true;
                        break 2;
                    }
                }
            }
            if ($dayAvailable) {
                $availableDates[] = $date->format('Y-m-d');
            }
        }

        return response()->json($availableDates);
    }

}
