<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ServicioResource;
use App\Http\Resources\EstilistaResource;

class ReservaResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'           => $this->id,
            'user_id'      => $this->user_id,
            'servicio'     => new ServicioResource($this->whenLoaded('servicio')),
            'estilista'    => new EstilistaResource($this->whenLoaded('estilista')),
            'fecha'        => $this->fecha,
            'hora'         => $this->hora,
            'estado'       => $this->estado,
            'created_at'   => $this->created_at->toDateTimeString(),
        ];
    }
}
