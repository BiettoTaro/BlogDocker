<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Blog extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, SoftDeletes;

    // Only allow title and content to be mass-assigned.
    protected $fillable = ['title', 'content', 'user_id'];

    // Specify the date columns for Carbon instances
    protected $dates = ['deleted_at'];

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function boot(){
        parent::boot();

        static::deleting(function ($blog) {
            // Soft delete all related comments
            if($blog->isForceDeleting()){
                $blog->comments()->forceDelete();
            } else {
                $blog->comments()->delete();
            }
        });
    }
}
