<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Retwit extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'retwits';

    protected $fillable = [
        'user_id', 'retwitable_id', 'retwitable_type'
    ];

    public function retwitable()
    {
        return $this->morphTo();
    }
}
