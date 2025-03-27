<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ServicioResource;
use App\Http\Resources\EstilistaResource;

/**
 * Transformador (Resource) de Reserva para la API.
 * Convierte un modelo Reserva en un array JSON-friendly.
 */
class ReservaResource extends JsonResource
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
            // Identificador único de la reserva
            'id' => $this->id,

            // Clave foránea del usuario que creó la reserva
            'user_id' => $this->user_id,

            // Servicio reservado (cargado si la relación 'servicio' fue eager-loaded)
            'servicio' => new ServicioResource($this->whenLoaded('servicio')),

            // Estilista asignado a la reserva (cargado si la relación 'estilista' fue eager-loaded)
            'estilista' => new EstilistaResource($this->whenLoaded('estilista')),

            // Fecha de la reserva (YYYY-MM-DD)
            'fecha' => $this->fecha,

            // Hora de la reserva (HH:mm)
            'hora' => $this->hora,

            // Estado actual de la reserva (PENDIENTE, CONFIRMADA, CANCELADA)
            'estado' => $this->estado,

            // Timestamp de creación en formato legible
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
