<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Blog;
use App\Models\Comment;
use App\Policies\BlogPolicy;
use App\Policies\CommentPolicy;

class AuthServiceProvider extends ServiceProvider
{

    protected $policies = [
        Blog::class => BlogPolicy::class,
        Comment::class => CommentPolicy::class,
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
        // Blog gates
        Gate::define('update-blog', function ($user, Blog $blog){
            // Only allwo the blog owner to update the blog
            return $user->id === $blog->user_id;
        });

        Gate::define('delete-blog', function($user, Blog $blog){
            return $user->id === $blog->user_id;
        });

        Gate::define('restore-blog', function($user, Blog $blog){
            return $user->id === $blog->user_id;
        });

        Gate::define('forceDelete-blog', function($user, Blog $blog){
            return $user->id === $blog->user_id;
        });

        // Comment gates
        Gate::define('update-comment', function ($user, Comment $comment) {
            return $user->id === $comment->user_id;
        });
        
        Gate::define('delete-comment', function ($user, Comment $comment) {
            return $user->id === $comment->user_id;
        });
    }
}
