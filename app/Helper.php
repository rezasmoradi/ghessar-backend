<?php


namespace App;


use Illuminate\Support\Str;

class Helper
{
    public static function toMobile($mobile)
    {
        if (!static::is_mobile($mobile)) {
            return '+98' . Str::substr($mobile, Str::length($mobile) - 10, 10);
        }

        return null;
    }

    public static function isValidUsername($username)
    {
        if (is_string($username)) {
            return preg_match('~^[a-zA-Z][a-zA-Z0-9_]{3,19}$~', $username);
        }
        return false;
    }

    public static function is_mobile($mobile)
    {
        return (bool)preg_match('~^(((\+|00)?98)|0)?9\d{9}$~', $mobile);
    }

    public static function randomCode()
    {
        return random_int(100000, 999999);
    }
}
