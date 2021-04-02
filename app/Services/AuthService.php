<?php


namespace App\Services;


use App\Helper;
use App\Http\Requests\AuthenticateRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Kavenegar\Exceptions\ApiException;
use Kavenegar\Exceptions\HttpException;
use Kavenegar\Laravel\Facade;

class AuthService
{
    public static function registerUsingMobile($mobile, $ip)
    {
        try {
            $code = Helper::randomCode();
            $mobile = Helper::toMobile($mobile);

            if ($user = User::query()->where('mobile', $mobile)->first()) {
                $user->username = $mobile;
                $user->verification_code = $code;
                $user->code_expired_at = now()->addMinutes(2);
                $user->country = geoip($ip)->country;
                $user->save();
            } else {
                User::query()->create([
                    'username' => $mobile,
                    'mobile' => $mobile,
                    'verification_code' => $code,
                    'code_expired_at' => now()->addMinutes(2),
                    'country' => geoip($ip)->country,
                ]);
            }
            $sender = env('KAVEHNEGAR_SENDER');
            $message = 'قصار\n' . 'کد ورود به برنامه:\n' . $code;
            $result = Facade::Send($sender, $mobile, $message);
            if ($result) {
                foreach ($result as $res) {
                    Log::info('res: ' . $res->statusText);
                }
            }
            return true;
        } catch (ApiException $exception) {
            Log::error($exception);
        } catch (HttpException $exception) {
            Log::error($exception);
        }
        return false;
    }

    public static function registerUsingEmail($email, $ip)
    {
        try {
            $code = Helper::randomCode();
            $data = ['code' => $code];
            if ($user = User::query()->where('email', $email)->first()) {
                $user->username = $email;
                $user->verification_code = $code;
                $user->code_expired_at = now()->addMinutes(2);
                $user->country = geoip($ip)->country;
                $user->save();
            } else {
                User::query()->create([
                    'username' => $email,
                    'email' => $email,
                    'verification_code' => $code,
                    'code_expired_at' => now()->addMinutes(2),
                    'country' => geoip($ip)->country,
                ]);
            }
            Mail::send(['html' => 'emails.verify'], $data, function (Message $message) use ($email) {
                $message->to($email);
                $message->from(env('MAIL_FROM_ADDRESS'));
                $message->subject('کد تایید ورود به قصار');
            });
            return true;
        } catch (\Exception $exception) {
            Log::error($exception);
            return false;
        }
    }

    public static function login(LoginRequest $request)
    {
        $user = User::query()->where(['username' => $request->username])->first();
        return is_null($user) || Hash::check($request->password, $user->password) === false ? false : $user;
    }

    public static function authenticate(AuthenticateRequest $request)
    {
        if ($request->has('mobile')) {
            $user = User::query()->where(['mobile' => $request->mobile, 'verification_code' => $request->code])->first();
            return $user && static::isNotExpired($user->code_expired_at) ? $user : false;
        } else {
            $user = User::query()->where(['email' => $request->email, 'verification_code' => $request->code])->first();
            return $user && static::isNotExpired($user->code_expired_at) ? $user : false;
        }
    }

    private static function isNotExpired($time)
    {
        return now()->subMinutes(2)->lessThanOrEqualTo($time);
    }
}
