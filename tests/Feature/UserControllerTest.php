<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateUser()
    {
        $data = [
            'name'  => 'Test User',
            'email' => 'test@example.com',
        ];

        $response = $this->postJson('/api/users', $data);

        // Check that the user was created and the response status is 201
        $response->assertStatus(201)
                 ->assertJsonFragment($data);

        // Verify the user exists in the database
        $this->assertDatabaseHas('users', $data);
    }

    public function testGetAllUsers()
    {
        // Create 3 users using the factory
        User::factory()->count(3)->create();

        $response = $this->getJson('/api/users');

        $response->assertStatus(200);

        // Assert that the response JSON contains exactly 3 users
        $this->assertCount(3, $response->json());
    }

    public function testGetUser()
    {
        $user = User::factory()->create();

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

        $response = $this->deleteJson("/api/users/{$user->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment(['message' => 'User deleted']);

        // Ensure the user is no longer in the database
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}
