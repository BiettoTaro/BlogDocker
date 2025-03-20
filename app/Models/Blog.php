<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Blog extends Model
{

    use HasFactory;

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
