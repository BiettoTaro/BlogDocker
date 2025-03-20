<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Blog;
use App\Models\Comment;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();
        Blog::factory(2)->create();
        Comment::factory(3)->create();

        
    }
}
