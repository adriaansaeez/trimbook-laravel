<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\User;
use App\Models\Perfil;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProfileApiTest extends TestCase
{
    use RefreshDatabase;

    private function authenticate(): User
    {
        $user = User::factory()->create(); // Ya crea perfil vía UserFactory
        $token = $user->createToken('test-token')->plainTextToken;
        $this->withHeader('Authorization', "Bearer {$token}");
        return $user;
    }

    public function test_show_profile()
    {
        $user = $this->authenticate();
        $perfil = $user->perfil; // Usa el perfil ya creado

        $response = $this->getJson("/api/v1/perfiles/{$perfil->id}");
        $response->assertOk()->assertJsonPath('data.usuario_id', $user->id);
    }

    public function test_update_profile()
    {
        $user = $this->authenticate();
        $perfil = $user->perfil;

        $response = $this->putJson("/api/v1/perfiles/{$perfil->id}", [
            'nombre' => 'Test User',
        ]);

        $response->assertOk()->assertJsonPath('data.nombre', 'Test User');
        $this->assertDatabaseHas('perfiles', ['id' => $perfil->id, 'nombre' => 'Test User']);
    }

    public function test_delete_profile()
    {
        $user = $this->authenticate();
        $perfil = $user->perfil;

        $this->deleteJson("/api/v1/perfiles/{$perfil->id}")
             ->assertNoContent();

        $this->assertDatabaseMissing('perfiles', ['id' => $perfil->id]);
    }
}
