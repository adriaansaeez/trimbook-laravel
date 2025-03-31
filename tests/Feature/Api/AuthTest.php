<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_returns_token()
    {
        $payload = [
            'username'=>'juan',
            'email'=>'juan@example.com',
            'password'=>'secret123',
            'password_confirmation'=>'secret123',
        ];

        $response = $this->postJson('/api/v1/register', $payload);

        $response->assertCreated()
                 ->assertJsonStructure(['token']);
        $this->assertDatabaseHas('users',['email'=>'juan@example.com']);
    }

    public function test_login_with_valid_credentials()
    {
        $user = User::factory()->create(['password'=>'secret123']);

        $response = $this->postJson('/api/v1/login', [
            'email'=>$user->email,
            'password'=>'secret123',
        ]);

        $response->assertOk()->assertJsonStructure(['token']);
    }

    public function test_login_with_invalid_credentials()
    {
        $response = $this->postJson('/api/v1/login', [
            'email'=>'no@exists.com',
            'password'=>'wrong',
        ]);

        $response->assertUnauthorized();
    }
}
