<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Like;
use App\Models\Comment;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'content',
        'media_url',
        'type',
    ];

    /**
     * The user who created this post.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Likes related to this post.
     */
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    /**
     * Comments related to this post.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
