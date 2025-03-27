<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ServicioResource;
use App\Http\Resources\HorarioResource;

/**
 * Transformador (Resource) de Estilista para la API.
 * Convierte un modelo Estilista en un array JSON-friendly.
 */
class EstilistaResource extends JsonResource
{
    /**
     * Convierte el recurso en un array que será devuelto como JSON.
     *
     * @param  Request  $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request)
    {
        return [
            // Identificador único del estilista
            'id' => $this->id,

            // Nombre descriptivo del estilista
            'nombre' => $this->nombre,

            // Datos básicos del usuario relacionado (relación 1:1)
            'user' => [
                'id'       => $this->user->id,
                'username' => $this->user->username,
                'email'    => $this->user->email,
            ],

            // Lista de servicios que el estilista ofrece
            // Solo se carga si la relación 'servicios' fue eager-loaded
            'servicios' => ServicioResource::collection(
                $this->whenLoaded('servicios')
            ),

            // Lista de horarios asignados al estilista
            // Solo se carga si la relación 'horarios' fue eager-loaded
            'horarios' => HorarioResource::collection(
                $this->whenLoaded('horarios')
            ),

            // Fecha y hora de creación del registro en formato legible
            'created_at' => $this->created_at->toDateTimeString(),

            // Fecha y hora de última actualización del registro
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
