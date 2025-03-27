<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ServicioResource;
use App\Http\Resources\HorarioResource;
use App\Http\Resources\HorarioResource;


class EstilistaResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'id'         => $this->id,
            'nombre'     => $this->nombre,
            'user'       => [
                'id'       => $this->user->id,
                'username' => $this->user->username,
                'email'    => $this->user->email,
            ],
            'servicios' => ServicioResource::collection($this->whenLoaded('servicios')),
            'horarios'   => HorarioResource::collection($this->whenLoaded('horarios')),
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
