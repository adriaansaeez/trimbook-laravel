<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Transformador (Resource) de Perfil para la API.
 * Convierte un modelo Perfil en un array JSON-friendly.
 */
class PerfilResource extends JsonResource
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
            // Identificador único del perfil
            'id' => $this->id,

            // Clave foránea que referencia al usuario propietario del perfil
            'usuario_id' => $this->usuario_id,

            // Datos personales del perfil
            'nombre'        => $this->nombre,
            'apellidos'     => $this->apellidos,
            'telefono'      => $this->telefono,
            'direccion'     => $this->direccion,

            // URL o ruta relativa de la foto de perfil
            'foto_perfil'   => $this->foto_perfil,

            // Enlace a la cuenta de Instagram (puede ser 'No especificado')
            'instagram_url' => $this->instagram_url,

            // Timestamps en formato legible
            'created_at'    => $this->created_at->toDateTimeString(),
            'updated_at'    => $this->updated_at->toDateTimeString(),
        ];
    }
}
