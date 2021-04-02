<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Twit extends Model
{
    use HasFactory, SoftDeletes, Notifiable;

    protected $fillable = [
        'user_id', 'body', 'media', 'reply_to'
    ];

    protected $withCount = ['likes', 'isLiked', 'retwits', 'isRetwitted', 'comments'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likes()
    {
        return $this->morphMany(Like::class, 'likable');
    }

    public function isLiked()
    {
        return $this->likes()->where('likes.user_id', auth()->id());
    }

    public function retwits()
    {
        return $this->morphMany(Retwit::class, 'retwitable');
    }

    public function isRetwitted()
    {
        return $this->retwits()->where('retwits.user_id', auth()->id());
    }

    public function reports()
    {
        return $this->morphMany(Report::class, 'reportable');
    }

    public function bookmark()
    {
        $this->hasMany(Bookmark::class);
    }
}
