<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Comment;
use App\Models\User;
use App\Models\Blog;
use App\Jobs\SendCommentNotificationJob;
use App\Mail\CommentNotification;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Mail;


class MailTest extends TestCase
{
    use RefreshDatabase;

    public function test_comment_creates_notification_job()
    {
        Bus::fake();
        $user = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' => $user->id]);
         

        $comment = Comment::factory()->create([
            'body'             => 'This is a comment on a blog.',
            'commentable_id'   => $blog->id,
            'commentable_type' => \App\Models\Blog::class,
        ]);

        dispatch((new SendCommentNotificationJob($comment)));

        Bus::assertDispatched(
            SendCommentNotificationJob::class, function ($job) use ($comment){
                return $job->getComment()->id === $comment->id;
            }); 
    }

    public function test_mailable_is_sent_on_comment_creation()
    {
        Mail::fake();

        $user = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' => $user->id]);
         

        $comment = Comment::factory()->create([
            'body'             => 'This is a comment on a blog.',
            'commentable_id'   => $blog->id,
            'commentable_type' => \App\Models\Blog::class,
        ]);

        dispatch((new SendCommentNotificationJob($comment)));

        Mail::assertSent(
            CommentNotification::class, function ($mail) use ($comment){
                return $mail->hasTo($comment->commentable->user->email)
                    && $mail->comment->id === $comment->id;
            });

    }
}
