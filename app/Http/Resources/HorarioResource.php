<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\EstilistaResource;

class HorarioResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'horario' => $this->horario,
            'registro_horas_semanales' => $this->registro_horas_semanales,
            'estilistas' => EstilistaResource::collection($this->whenLoaded('estilistas')),
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
