<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function follow(User $follower, User $following)
    {
        return $follower->id !== $following->id &&
            $follower->followings()->where('following', $following->id)->count() === 0;
    }

    public function unfollow(User $follower, User $following)
    {
        return $follower->followings()->where('following', $following->id)->count();
    }

    public function restrict(User $restricter, User $restricting)
    {
        return $this->addValue($restricter, $restricting, 'restrict');
    }

    public function unrestrict(User $restricter, User $restricting)
    {
        return $this->removeValue($restricter, $restricting, 'restrict');
    }

    public function mute(User $muter, User $muting)
    {
        if ($muter->followings()->where('following', $muting->id)->count()) {
            return $this->addValue($muter, $muting, 'mute');
        }
        return false;
    }

    public function unmute(User $muter, User $muting)
    {
        return $this->removeValue($muter, $muting, 'mute');
    }

    public function block(User $blocker, User $blocking)
    {
        return $this->addValue($blocker, $blocking, 'block');
    }

    public function unblock(User $blocker, User $blocking)
    {
        return $this->removeValue($blocker, $blocking, 'block');
    }

    private function addValue(User $limiter, User $limiting, $limit)
    {
        switch ($limit) {
            case 'restrict':
                $values = $limiter->getRestricts();
                break;
            case 'mute':
                $values = $limiter->getMutes();
                break;
            case 'block':
                $values = $limiter->getBlocks();
        }
        $isNotSelf = $limiter->id !== $limiting->id;
        return empty($values) ? $isNotSelf : $isNotSelf && !in_array($limiting->id, json_decode($values));
    }

    private function removeValue(User $limiter, User $limiting, $limit)
    {
        switch ($limit) {
            case 'restrict':
                $values = $limiter->getRestricts();
                break;
            case 'mute':
                $values = $limiter->getMutes();
                break;
            case 'block':
                $values = $limiter->getBlocks();
        }
        return empty($values) ? true : in_array($limiting->id, json_decode($values));
    }
}
