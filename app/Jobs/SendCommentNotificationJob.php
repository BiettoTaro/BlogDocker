<?php

namespace App\Jobs;

use App\Mail\CommentNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\User;


class SendCommentNotificationJob implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue, SerializesModels;

    protected $comment;

    /**
     * Create a new job instance.
     */
    public function __construct($comment)
    {
        $this->comment = $comment;
    }


    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $commentable = $this->comment->commentable;
        // Determine the recipient user with a 'commentable' relation
        $recipient = $commentable instanceof User ? $commentable : $commentable->user;
        Mail::to($recipient->email)->send(new CommentNotification($this->comment));
    }

    public function getComment()
    {
        return $this->comment;
    }
}
