<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PerfilResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'usuario_id'    => $this->usuario_id,
            'nombre'        => $this->nombre,
            'apellidos'     => $this->apellidos,
            'telefono'      => $this->telefono,
            'direccion'     => $this->direccion,
            'foto_perfil'   => $this->foto_perfil,
            'instagram_url' => $this->instagram_url,
            'created_at'    => $this->created_at->toDateTimeString(),
            'updated_at'    => $this->updated_at->toDateTimeString(),
        ];
    }
}
