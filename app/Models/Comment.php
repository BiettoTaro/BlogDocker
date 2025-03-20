<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;
    
    protected $fillable = ['body', 'user_id'];

    // Defines the polymorphic relation (could be a Blog or a User)
    public function commentable()
    {
        return $this->morphTo();
    }

    // If the comment is from an authenticated user, this relation connects to that user.
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
