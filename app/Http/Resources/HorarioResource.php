<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\EstilistaResource;

/**
 * Transformador (Resource) de Horario para la API.
 * Convierte un modelo Horario en un array JSON-friendly.
 */
class HorarioResource extends JsonResource
{
    /**
     * Convierte el recurso en un array que será devuelto como JSON.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            // Identificador único del horario
            'id' => $this->id,

            // Nombre descriptivo del horario
            'nombre' => $this->nombre,

            // Estructura JSON completa con días e intervalos
            'horario' => $this->horario,

            // Total de horas semanales calculadas automáticamente
            'registro_horas_semanales' => $this->registro_horas_semanales,

            // Lista de estilistas asociados a este horario
            // Solo se incluye si la relación 'estilistas' fue eager-loaded
            'estilistas' => EstilistaResource::collection(
                $this->whenLoaded('estilistas')
            ),

            // Timestamp de creación en formato legible
            'created_at' => $this->created_at->toDateTimeString(),

            // Timestamp de última actualización en formato legible
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
