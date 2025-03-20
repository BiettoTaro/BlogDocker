<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Blog;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentControllerTest extends TestCase
{
    use RefreshDatabase;

    
    public function test_authenticated_user_can_create_comment_on_blog()
    {
        // Create a user and simulate authentication.
        $user = User::factory()->create();
        $this->actingAs($user);
        $blog = Blog::factory()->create(['user_id' => $user->id]);

        $data = [
            'body'             => 'This is a comment on a blog.',
            'commentable_id'   => $blog->id,
            'commentable_type' => Blog::class,
        ];

        $response = $this->postJson('/api/comments', $data);

        $response->assertStatus(201)
                 ->assertJsonFragment([
                     'body'             => 'This is a comment on a blog.',
                     'commentable_id'   => $blog->id,
                     'commentable_type' => Blog::class,
                     'user_id'          => $user->id,
                 ]);

        // Confirm the comment was stored in the database.
        $this->assertDatabaseHas('comments', [
            'body'             => 'This is a comment on a blog.',
            'commentable_id'   => $blog->id,
            'commentable_type' => Blog::class,
            'user_id'          => $user->id,
        ]);
    }

    /**
     * Test that an authenticated user can create a comment on a User (author).
     */
    public function test_authenticated_user_can_create_comment_on_user()
    {
        // Create a user for authentication.
        $user = User::factory()->create();
        $this->actingAs($user);

        // Create a target user to comment on.
        $targetUser = User::factory()->create();

        $data = [
            'body'             => 'This is a comment on a user.',
            'commentable_id'   => $targetUser->id,
            'commentable_type' => 'App\Models\User',
        ];

        $response = $this->postJson('/api/comments', $data);

        $response->assertStatus(201)
                 ->assertJsonFragment([
                     'body'             => 'This is a comment on a user.',
                     'commentable_id'   => $targetUser->id,
                     'commentable_type' => 'App\Models\User',
                     'user_id'          => $user->id,
                 ]);

        $this->assertDatabaseHas('comments', [
            'body'             => 'This is a comment on a user.',
            'commentable_id'   => $targetUser->id,
            'commentable_type' => 'App\Models\User',
            'user_id'          => $user->id,
        ]);
    }

    /**
     * Test that an unauthenticated user cannot create a comment.
     */
    public function test_unauthenticated_user_cannot_create_comment()
    {
        $user = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' => $user->id]);

        $data = [
            'body'             => 'This comment should not be created.',
            'commentable_id'   => $blog->id,
            'commentable_type' => Blog::class,
        ];

        $response = $this->postJson('/api/comments', $data);
        $response->assertStatus(401); // Or 403, depending on your middleware configuration.
    }
}
