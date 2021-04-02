<?php

namespace App\Http\Controllers;

use App\Http\Requests\BlockRequest;
use App\Http\Requests\CheckUsernameRequest;
use App\Http\Requests\FollowRequest;
use App\Http\Requests\MuteRequest;
use App\Http\Requests\ReportUserRequest;
use App\Http\Requests\RestrictRequest;
use App\Http\Requests\SetProfileRequest;
use App\Http\Requests\UnblockRequest;
use App\Http\Requests\UnFollowRequest;
use App\Http\Requests\UnmuteRequest;
use App\Http\Requests\UnRestrictRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\MyResouece;
use App\Http\Resources\UserResource;
use App\Models\Report;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function me(Request $request)
    {
        $user = User::with(['twits', 'followers', 'followings'])->where('id', auth()->id())->first();
        return response(new MyResouece($user), Response::HTTP_OK);
    }

    public function user(Request $request)
    {
        $user = $request->route('user');
        return response(new UserResource($user), Response::HTTP_OK);
    }

    public function checkUsername(CheckUsernameRequest $request)
    {
        if (UserService::checkUsername($request)) {
            return response(null, Response::HTTP_OK);
        } else {
            return response(['message' => 'username already exists!'], Response::HTTP_BAD_REQUEST);
        }
    }

    public function follow(FollowRequest $request)
    {
        if (UserService::follow($request)) {
            return response(['message' => 'از این لحظه، این صفحه، توسط شما دنبال می شود.'], Response::HTTP_OK);
        }
        return response(['message' => 'خطایی در دنبال کردن کاربر رخ داده است'], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function unfollow(UnFollowRequest $request)
    {
        if (UserService::unfollow($request)) {
            return response(['message' => 'از این لحظه، این صفحه، دیگر توسط شما دنبال نمی شود.'], Response::HTTP_OK);
        }
        return response(['message' => 'خطایی در حذف دنبال کردن کاربر رخ داده است'], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function restrict(RestrictRequest $request)
    {
        if (UserService::restrict($request)) {
            return response(['message' => 'کاربر به لیست محدودیت های شما اضافه شد.'], Response::HTTP_OK);
        }
        return response(['message' => 'خطایی در محدود کردن کاربر رخ داده است'], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function unrestrict(UnRestrictRequest $request)
    {
        if (UserService::unrestrict($request)) {
            return response(['message' => 'کاربر از لیست محدودیت های شما حذف شد.'], Response::HTTP_OK);
        }
        return response(['message' => 'خطایی در حذف محدودیت کاربر رخ داده است'], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function mute(MuteRequest $request)
    {
        if (UserService::mute($request)) {
            return response(['message' => 'فعالیتهای کاربر برای شما بیصدا شد.'], Response::HTTP_OK);
        }
        return response(['message' => 'خطایی در بیصدا کردن فعالیت کاربر رخ داده است'], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function unmute(UnmuteRequest $request)
    {
        if (UserService::unmute($request)) {
            return response(['message' => 'صدای فعالیتهای کاربر برای شما فعال شد.'], Response::HTTP_OK);
        }
        return response(['message' => 'خطایی در فعالسازی صدای فعالیت کاربر رخ داده است'], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function block(BlockRequest $request)
    {
        if (UserService::block($request)) {
            return response(['message' => 'کاربر به لیست سیاه شما اضافه شد.'], Response::HTTP_OK);
        }
        return response(['message' => 'خطایی در افرودن کاربر به لیست سیاه رخ داده است'], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function unblock(UnblockRequest $request)
    {
        if (UserService::unblock($request)) {
            return response(['message' => 'کاربر از لیست سیاه شما اضافه شد.'], Response::HTTP_OK);
        }
        return response(['message' => 'خطایی در حذف کاربر ار لیست سیاه رخ داده است'], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function followers(Request $request)
    {
        $followers = $request->user()->getFollowers()->paginate(10);
        return response(UserResource::collection($followers)->response()->getData(true), Response::HTTP_OK);
    }

    public function followings(Request $request)
    {
        $followings = $request->user()->getFollowings()->paginate(10);
        return response(UserResource::collection($followings)->response()->getData(true), Response::HTTP_OK);
    }

    public function blocks(Request $request)
    {
        $blockList = $request->user()->getBlocks()->paginate(10);
        return response(UserResource::collection($blockList)->response()->getData(true), Response::HTTP_OK);
    }

    public function setProfilePhoto(SetProfileRequest $request)
    {
        if ($photoName = UserService::setAvatar($request)) {
            return response(['filename' => $photoName], Response::HTTP_OK);
        }
        return response(['message' => 'آپلود عکس با مشکل مواجه شد.'], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function update(UpdateUserRequest $request)
    {
        if (UserService::update($request)) {
            return response(['user' => new MyResouece($request->user())], Response::HTTP_ACCEPTED);
        }
        return response(['message' => 'بروزرسانی اطلاعات کاربر با خطا مواجه شد.'], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function report(ReportUserRequest $request)
    {
        $reporter = $request->user();
        $reporting = $request->route('user');
        $reason = $request->post('reason');
        Report::query()->create([
            'reporter_id' => $reporter->id,
            'reportable_id' => $reporting->id,
            'reportable_type' => 'user',
            'reason_id' => $reason,
        ]);
        return response(['message' => 'گزارش با موفقیت ثبت شد.'], Response::HTTP_CREATED);
    }
}
