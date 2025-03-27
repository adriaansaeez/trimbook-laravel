<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\EstilistaResource;

/**
 * Transformador (Resource) de Servicio para la API.
 * Convierte un modelo Servicio en un array JSON-friendly.
 */
class ServicioResource extends JsonResource
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
            // Identificador único del servicio
            'id' => $this->id,

            // Nombre descriptivo del servicio
            'nombre' => $this->nombre,

            // Descripción detallada del servicio
            'descripcion' => $this->descripcion,

            // Precio en formato decimal (ej: 49.99)
            'precio' => $this->precio,

            // Duración estimada del servicio en minutos
            'duracion' => $this->duracion,

            // Lista de estilistas que ofrecen este servicio
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
