<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PagoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'reserva_id' => $this->reserva_id,
            'monto' => $this->monto,
            'metodo_pago' => $this->metodo_pago,
            'estado' => $this->estado,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'reserva' => new ReservaResource($this->whenLoaded('reserva')),
        ];
    }
} 