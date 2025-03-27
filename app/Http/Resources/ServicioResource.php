<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\EstilistaResource;

class ServicioResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'          => $this->id,
            'nombre'      => $this->nombre,
            'descripcion' => $this->descripcion,
            'precio'      => $this->precio,
            'duracion'    => $this->duracion,
            'estilistas' => EstilistaResource::collection($this->whenLoaded('estilistas')),
            'created_at'  => $this->created_at->toDateTimeString(),
            'updated_at'  => $this->updated_at->toDateTimeString(),
        ];
    }
}
