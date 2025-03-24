<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateUser()
    {
        $data = [
            'name'  => 'Test User',
            'email' => 'test@example.com',
            'password' => 'secret999',
        ];

        $response = $this->postJson('/api/users', $data);

        // Check that the user was created and the response status is 201
        $response->assertStatus(201)
                 ->assertJsonFragment([
                    'name'  => 'Test User',
                    'email' => 'test@example.com',
                 ]);

        // Verify the user exists in the database
        $this->assertDatabaseHas('users', [
            'name'  => 'Test User',
            'email' => 'test@example.com',
        ]);
    }

    public function testGetAllUsers()
    {

        $user = User::factory()->create();
        Sanctum::actingAs($user);

        // Create 3 users using the factory
        User::factory()->count(2)->create();

        $response = $this->getJson('/api/users');

        $response->assertStatus(200);

        // Assert that the response JSON contains exactly 3 users
        $this->assertCount(3, $response->json());
    }

    public function testGetUser()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson("/api/users/{$user->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'email' => $user->email,
                     'name'  => $user->name,
                 ]);
    }

    public function testUpdateUser()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $updateData = [
            'name'  => 'Updated Name',
            'email' => 'updated@example.com',
        ];

        $response = $this->putJson("/api/users/{$user->id}", $updateData);

        $response->assertStatus(200)
                 ->assertJsonFragment($updateData);

        // Verify that the database now contains the updated values
        $this->assertDatabaseHas('users', array_merge(['id' => $user->id], $updateData));
    }

    public function testDeleteUser()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->deleteJson("/api/users/{$user->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment(['message' => 'User deleted']);

        // Ensure the user is no longer in the database
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}
