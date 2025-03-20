<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Blog;
use App\Models\Comment;
use App\Models\User;

class BlogTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function blog_belongs_to_author()
    {
        $user = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $blog->author);
        $this->assertEquals($user->id, $blog->author->id);
    }

    /** @test */
    public function blog_has_comments()
    {
        $user = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' => $user->id]);
        $comment = Comment::factory()->create([
            'commentable_id' => $blog->id,
            'commentable_type' => Blog::class,
        ]);

        $this->assertTrue($blog->comments->contains($comment));
    }
}
