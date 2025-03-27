<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserApiTest extends TestCase
{
    use RefreshDatabase;

    protected function authenticate()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;
        $this->withHeader('Authorization',"Bearer {$token}");
        return $user;
    }

    public function test_index_returns_paginated_users()
    {
        User::factory()->count(3)->create();
        $this->authenticate();

        $response = $this->getJson('/api/v1/users');
        $response->assertOk()->assertJsonStructure(['data','links','meta']);
    }

    public function test_store_creates_user()
    {
        $this->authenticate();

        $payload = [
            'username'=>'pedro',
            'email'=>'pedro@example.com',
            'password'=>'secret123',
            'password_confirmation'=>'secret123',
        ];

        $response = $this->postJson('/api/v1/users',$payload);
        $response->assertCreated()->assertJsonFragment(['email'=>'pedro@example.com']);
        $this->assertDatabaseHas('users',['email'=>'pedro@example.com']);
    }

    public function test_show_returns_user()
    {
        $user = $this->authenticate();
        $response = $this->getJson("/api/v1/users/{$user->id}");
        $response->assertOk()->assertJsonPath('data.id',$user->id);
    }

    public function test_update_changes_user()
    {
        $user = $this->authenticate();
        $response = $this->putJson("/api/v1/users/{$user->id}", ['username'=>'nuevo']);
        $response->assertOk()->assertJsonPath('data.username','nuevo');
        $this->assertDatabaseHas('users',['username'=>'nuevo']);
    }

    public function test_destroy_deletes_user()
    {
        $user = $this->authenticate();
        $response = $this->deleteJson("/api/v1/users/{$user->id}");
        $response->assertNoContent();
        $this->assertDatabaseMissing('users',['id'=>$user->id]);
    }
}
