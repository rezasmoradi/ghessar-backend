<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Security extends Model
{
    use HasFactory;

    protected $table = 'securities';

    protected $fillable = [
        'user_id', 'mutes', 'restricts'
    ];
}
