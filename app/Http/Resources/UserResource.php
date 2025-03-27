<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Transformador (Resource) de User para la API.
 * Convierte un modelo User en un array JSON-friendly.
 */
class UserResource extends JsonResource
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
            // Identificador único del usuario
            'id' => $this->id,

            // Nombre de usuario único (username)
            'username' => $this->username,

            // Dirección de correo electrónico asociada
            'email' => $this->email,

            // Timestamp de creación en formato legible
            'created_at' => $this->created_at->toDateTimeString(),

            // Timestamp de última actualización en formato legible
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
