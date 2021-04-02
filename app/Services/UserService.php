<?php


namespace App\Services;


use App\Http\Requests\BlockRequest;
use App\Http\Requests\CheckUsernameRequest;
use App\Http\Requests\FollowRequest;
use App\Http\Requests\MuteRequest;
use App\Http\Requests\RestrictRequest;
use App\Http\Requests\SetProfileRequest;
use App\Http\Requests\UnblockRequest;
use App\Http\Requests\UnFollowRequest;
use App\Http\Requests\UnmuteRequest;
use App\Http\Requests\UnRestrictRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Follow;
use App\Models\Security;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UserService
{
    public static function checkUsername(CheckUsernameRequest $request)
    {
        return User::query()
                ->where('username', $request->get('username'))
                ->where('id', '<>', $request->user()->id)
                ->count() === 0;
    }

    public static function follow(FollowRequest $request)
    {
        $follower = $request->user();
        return Follow::query()->create(['follower' => $follower->id, 'following' => $request->route('user')->id]);
    }

    public static function unfollow(UnFollowRequest $request)
    {
        $follower = $request->user();
        return Follow::query()
            ->where(['follower' => $follower->id, 'following' => $request->route('user')->id])
            ->delete();
    }

    public static function restrict(RestrictRequest $request)
    {
        return static::setValues($request, 'restricts');
    }

    public static function unrestrict(UnRestrictRequest $request)
    {
        return static::unsetValues($request, 'restricts');
    }

    public static function mute(MuteRequest $request)
    {
        return static::setValues($request, 'mutes');
    }

    public static function unmute(UnmuteRequest $request)
    {
        return static::unsetValues($request, 'mutes');
    }

    public static function block(BlockRequest $request)
    {
        return static::setValues($request, 'blocks');
    }

    public static function unblock(UnblockRequest $request)
    {
        return static::unsetValues($request, 'blocks');
    }

    private static function setValues($request, $column)
    {
        $user = $request->user();
        switch ($column) {
            case 'restricts':
                $values = json_decode($user->getRestricts());
                break;
            case 'mutes':
                $values = json_decode($user->getMutes());
                break;
            case 'blocks':
                $values = json_decode($user->getBlocks());
                break;
        }
        if (empty($values)) {
            $values = [$request->route('user')->id];
        } else {
            $values[] = $request->route('user')->id;
        }
        return Security::query()->where('user_id', $user->id)->update([$column => $values]);
    }

    private static function unsetValues($request, $column)
    {
        $user = $request->user();
        switch ($column) {
            case 'restricts':
                $values = json_decode($user->getRestricts());
                break;
            case 'mutes':
                $values = json_decode($user->getMutes());
                break;
            case 'blocks':
                $values = json_decode($user->getBlocks());
                break;
        }
        if (!empty($values)) {
            $valueId = array_search($request->route('user')->id, $values);
            unset($values[$valueId]);
            $values = array_values($values) ?: [];
            return Security::query()->where('user_id', $user->id)->update([$column => $values]);
        }
        return true;
    }

    public static function update(UpdateUserRequest $request)
    {
        try {
            $user = auth()->user();
            if ($request->has('first_name')) $user->first_name = $request->first_name;
            if ($request->has('last_name')) $user->last_name = $request->last_name;
            if ($request->has('username')) $user->username = $request->username;
            if ($request->has('bio')) $user->bio = $request->bio;
            if ($request->has('country')) $user->country = $request->country;
            $user->save();
            return $user;
        } catch (\Exception $exception) {
            Log::error($exception);
            return false;
        }
    }

    public static function setAvatar(SetProfileRequest $request)
    {
        $user = auth()->user();
        $photo = $request->file('photo');
        $photoName = $request->user()->id . '_' . $photo->getClientOriginalName();
        $save = Storage::disk('avatar')->putFileAs($request->user()->id, $photo, $photoName);
        if ($save) {
            $user->avatar = $photoName;
            $user->save();
            return Storage::disk('avatar')->url($request->user()->id . DIRECTORY_SEPARATOR . $photoName);
        }
        return false;
    }
}
