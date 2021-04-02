<?php

namespace App\Rules;

use App\Helper;
use App\Models\User;
use Illuminate\Contracts\Validation\Rule;

class UsernameRule implements Rule
{
    /**
     * @var User
     */
    private $user;
    /**
     * @var bool
     */
    private $skipUserCheck;

    /**
     * Create a new rule instance.
     *
     * @param User $user
     * @param bool $skipUserCheck
     */
    public function __construct(User $user = null, $skipUserCheck = false)
    {
        $this->user = $user;
        $this->skipUserCheck = $skipUserCheck;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (!Helper::isValidUsername($value)) return false;

        if (!$this->skipUserCheck) {
            if ($this->user) {
                return User::query()->where('username', $value)->count() === 0;
            } else {
                return User::query()
                        ->where('username', $value)
                        ->where('id', '<>', $this->user->id)
                        ->count() === 0;
            }
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The username is not valid!';
    }
}
