<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $fillable = ['user_id', 'title', 'content'];

    // A blog belongs to a user (the author)
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // A blog can have many comments (polymorphic relation)
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
