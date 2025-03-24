<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_and_receive_token()
    {
        $user = User::factory()->create([
            'email' => 'john@example.com',
            'password' => 'secret999',
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'john@example.com',
            'password' => 'secret999',
        ]);

        $response->assertStatus(200)
                        ->assertJsonStructure([
                            'token',
                            'user' => [
                                'id',
                                'email',
                            ]
                            ]);
    }
    
    public function test_unauth_user_cannot_access_protected_route()
    {
        $response = $this->getJson('/api/blogs');

        $response->assertStatus(401);
    }

    public function test_auth_user_can_access_protected_route()
    {
        $user = User::factory()->create();

        // Simulate authentication with sanctum
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/blogs');

        $response->assertStatus(200);
    }

}
