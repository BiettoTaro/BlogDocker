<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Blog;
use App\Models\User;
use App\Models\Comment;

class CommentControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_can_comment()
    {
        $user = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' =>$user->id]);


        $response = $this->postJson('/api/comments', [
            'commentable_id' => $blog->id,
            'commentable_type' => 'blog',
            'body' => 'Some content',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('comments', [
            'commentable_id' => $blog->id,
            'commentable_type' => Blog::class,
            'body' => 'Some content',
            'user_id' => null,
        ]);
    }

    /** @test */
    public function auth_user_creates_comments()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $blog = Blog::factory()->create(['user_id' =>$user->id]);

        $response = $this->postJson('/api/comments', [
            'commentable_id' => $blog->id,
            'commentable_type' => 'blog',
            'body' => 'Comment from user',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('comments', [
            'commentable_id' => $blog->id,
            'commentable_type' => Blog::class,
            'body' => 'Comment from user',
            'user_id' => $user->id,
        ]);
    }
}
