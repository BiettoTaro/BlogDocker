<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Comment;
use App\Models\Blog;
use App\Models\User;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function comment_belong_to_blog()
    {
        $user = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' => $user->id]);
        $comment = Comment::factory()->create([
            'user_id' => $user->id,
            'commentable_id' => $blog->id,
            'commentable_type' => Blog::class,
        ]);

        $this->assertInstanceOf(Blog::class, $comment->commentable);
        $this->assertEquals($blog->id, $comment->commentable->id);
    }

    /** @test */
    public function comment_belong_to_user()
    {
        $user = User::factory()->create();
        $comment = Comment::factory()->create([
            'user_id' => $user->id,
            'commentable_id' => $user->id,
            'commentable_type' => User::class,
        ]);

        $this->assertInstanceOf(User::class, $comment->commentable);
        $this->assertEquals($user->id, $comment->commentable->id);
    }

    /** @test */
    public function comment_has_author()
    {
        $user = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' => $user->id]);
        $comment = $blog->comments()->create([
            'user_id' => $user->id,
            'body' => 'Some content',
        ]);

        $this->assertInstanceOf(User::class, $comment->user);
        $this->assertInstanceOf(Blog::class, $comment->commentable);
        $this->assertEquals($user->id, $comment->user->id);
    }
}
