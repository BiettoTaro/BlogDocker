<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Blog;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

class BlogControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_cannot_create_blogs()
    {
        $data = [
            'title'   => 'My First Blog',
            'content' => 'Some content',
        ];

        // Since no user is authenticated, this should be intercepted by the auth middleware.
        $response = $this->postJson('/api/blogs', $data);

        $response->assertStatus(401);
    }

    /** @test */
    public function auth_user_can_create_blogs()
    {
        // Create an authenticated user
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = [
            'title'   => 'My First Blog',
            'content' => 'Some content',
        ];

        $response = $this->postJson('/api/blogs', $data);

        $response->assertStatus(201)
                 ->assertJsonFragment([
                     'title'   => 'My First Blog',
                     'content' => 'Some content',
                     'user_id' => $user->id,  // Check that the blog is associated with the authenticated user
                 ]);

        // Also confirm in the database that the blog exists.
        $this->assertDatabaseHas('blogs', [
            'title'   => 'My First Blog',
            'content' => 'Some content',
            'user_id' => $user->id,
        ]);
    }

    /** @test */
    public function it_shows_blogs()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        
        $blog = Blog::factory()->create(['user_id' => $user->id]);

        $response = $this->getJson("/api/blogs/{$blog->id}");

        $response->assertStatus(200)->assertJson([
            'id' =>$blog->id,
            'title' => $blog->title,
        ]);
    }

    /** @test */
    public function it_updates_blogs()
    {
        $user = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' =>$user->id]);

        $this->actingAs($user);

        $response = $this->putJson("/api/blogs/{$blog->id}", [
            'title' => 'Updated Title',
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseHas('blogs', [
            'id' => $blog->id,
            'title' => 'Updated Title',
        ]);
    }

    /** @test */
    public function it_deletes_blogs()
    {
        $user = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' => $user->id]);

        Sanctum::actingAs($user);

        $response = $this->deleteJson("/api/blogs/{$blog->id}");
        $response->assertStatus(204);

        $this->assertSoftDeleted('blogs', [
            'id' => $blog->id,
        ]);
    }
}
