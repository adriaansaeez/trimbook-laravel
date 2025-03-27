<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegistrationApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_register_returns_token_and_creates_user()
    {
        $payload = [
            'username'=>'juan',
            'email'=>'juan@example.com',
            'password'=>'secret123',
            'password_confirmation'=>'secret123',
        ];

        $response = $this->postJson('/api/v1/register', $payload);

        $response->assertCreated()->assertJsonStructure(['token']);
        $this->assertDatabaseHas('users',['email'=>'juan@example.com']);
    }
}
