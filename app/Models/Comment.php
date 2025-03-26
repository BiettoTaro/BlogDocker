<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = ['body', 'commentable_id', 'commentable_type', 'user_id'];
    // Specify the date columns for Carbon instances
    protected $dates = ['deleted_at'];

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
