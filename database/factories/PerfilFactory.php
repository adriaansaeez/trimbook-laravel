<?php

namespace Database\Factories;

use App\Models\Perfil;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PerfilFactory extends Factory
{
    protected $model = Perfil::class;

    public function definition()
    {
        return [
            'usuario_id'    => User::factory(),
            'nombre'        => $this->faker->firstName(),
            'apellidos'     => $this->faker->lastName(),
            'telefono'      => $this->faker->phoneNumber(),
            'direccion'     => $this->faker->address(),
            'foto_perfil'   => 'default.jpg',
            'instagram_url' => $this->faker->url(),
        ];
    }
}
