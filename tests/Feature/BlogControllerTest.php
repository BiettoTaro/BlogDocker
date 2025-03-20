<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Blog;
use App\Models\User;

class BlogControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_cannot_create_blogs()
    {
        $response = $this->postJson('/api/blogs',[
            'title' => 'My First Blog',
            'content' => 'Some content',
        ]);


        $response->assertStatus(401);
    }

    /** @test */
    public function auth_user_can_create_blogs()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'api');

        $response = $this->postJson('/api/blogs', [
            'title' => 'My First Blog',
            'content' => 'Some content',
            'user_id' => $user->id,
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('blogs', [
            'title' => 'My First Blog',
            'content' => 'Some content',
            'user_id' => $user->id,
        ]);
    }

    /** @test */
    public function it_shows_blogs()
    {
        $user = User::factory()->create();
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
        $blog = Blog::create()->create(['user_id' => $user->id]);

        $this->actingAs($user);

        $response = $this->deleteJson("/api/blogs/{$blog->id}");
        $response->assertStatus(204);

        $this->assertDatabaseMissing('blogs', [
            'id' => $blog->id,
        ]);
    }
}
