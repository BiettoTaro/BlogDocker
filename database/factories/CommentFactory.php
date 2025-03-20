<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\User;
use App\Models\Blog;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    protected $model = Comment::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'body' => $this->faker->sentence,
            'commentable_id' => 0, // default value
            'commentable_type' => Blog::class,
        ];
    }
}
