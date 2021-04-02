<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthenticateRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\Security;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        if ($request->has('mobile') && AuthService::registerUsingMobile($request->mobile, $request->ip())) {
            return response('کد تأیید به شماره همراه شما ارسال شد.', Response::HTTP_CREATED);
        } elseif ($request->has('email') && AuthService::registerUsingEmail($request->email, $request->ip())) {
            return response('کد تأیید به ایمیل شما ارسال شد.', Response::HTTP_CREATED);
        }
        return response(null, Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function authenticate(AuthenticateRequest $request)
    {
        if ($user = AuthService::authenticate($request)) {
            if (is_null(Security::query()->where('user_id', $user->id)->first())) {
                Security::query()->create([
                    'user_id' => $user->id,
                    'blocks' => json_encode([]),
                    'restricts' => json_encode([]),
                    'mutes' => json_encode([]),
                ]);
            }
            $user->verification_code = null;
            $user->code_expired_at = null;
            $user->verified_at = now();
            $token = $user->createToken('Personal Access Token')->accessToken;
            $user->save();
            return response(['token' => $token], Response::HTTP_OK);
        }
        return response(null, Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function login(LoginRequest $request)
    {
        if ($user = AuthService::login($request)) {
            $token = $user->createToken('Personal Access Token')->accessToken;
            $user->save();
            return response(['token' => $token], Response::HTTP_OK);
        }
        return response(null, Response::HTTP_NOT_FOUND);
    }

    public function auth(LoginRequest $request)
    {
        if (User::query()->where('username', $request->username)->count() === 1) {
            return response(['message' => 'نام کاربری تکراری است.'], Response::HTTP_BAD_REQUEST);
        }
        $user = $request->user();
        $user->username = $request->post('username');
        $user->password = bcrypt($request->post('password'));
        $user->save();

        return response(null, Response::HTTP_ACCEPTED);
    }

    public function logout(Request $request)
    {
        $token = $request->user()->token();
        $token->revoke();
        return response(['message' => 'کاربر با موفقیت خارج شد.'], Response::HTTP_OK);
    }
}
