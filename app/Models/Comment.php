<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'twit_id', 'body', 'media', 'reply_to',
    ];

//    protected $with = ['replies'];

    protected $withCount = ['likes'];

    public function twit()
    {
        return $this->belongsTo(Twit::class);
    }

    public function likes()
    {
        return $this->morphMany(Like::class, 'likable');
    }

    public function retwits()
    {
        return $this->morphMany(Retwit::class, 'retwitable');
    }

    public function report()
    {
        return $this->morphMany(Report::class, 'reportable');
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'id', 'reply_to');
    }
}
