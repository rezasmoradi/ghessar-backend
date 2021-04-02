<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    const USER_TYPE_ADMIN = 'admin';
    const USER_TYPE_INSPECTOR = 'inspector';
    const USER_TYPE_USER = 'user';
    const USER_TYPES = [self::USER_TYPE_USER, self::USER_TYPE_INSPECTOR, self::USER_TYPE_ADMIN];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type', 'first_name', 'last_name', 'username', 'password', 'mobile', 'email',
        'verification_code', 'code_expired_at', 'verified_at', 'avatar', 'bio', 'country',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'verification_code',
        'code_expired_at',
        'verified_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'verified_at' => 'datetime',
        'code_expired_at' => 'datetime'
    ];

    protected $with = ['followers', 'followings', 'isFollowed'];

    public function twits()
    {
        return $this->hasMany(Twit::class);
    }

    public function followers()
    {
        return $this->hasMany(Follow::class, 'following');
    }

    public function followings()
    {
        return $this->hasManyThrough(
            User::class,
            Follow::class,
            'follower',
            'id',
            'id',
            'following'
        );
    }

    public function isFollowed()
    {
        return $this->followings()->where('following', auth()->id());
    }

    public function getRestricts()
    {
        return Security::query()->where('user_id', $this->id)->first()->restricts;
    }

    public function getMutes()
    {
        return Security::query()->where('user_id', $this->id)->first()->mutes;
    }

    public function getBlocks()
    {
        return Security::query()->where('user_id', $this->id)->first()->blocks;
    }

    public function reports()
    {
        return $this->morphMany(Report::class, 'reportable');
    }

    public function bookmark()
    {
        return $this->hasMany(Bookmark::class);
    }
}
