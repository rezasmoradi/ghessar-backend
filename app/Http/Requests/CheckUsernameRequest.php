<?php

namespace App\Http\Requests;

use App\Rules\UsernameRule;
use Illuminate\Foundation\Http\FormRequest;

class CheckUsernameRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => ['required', new UsernameRule(null, true)]
        ];
    }

    public function getValidatorInstance()
    {
        $this->merge(['username' => $this->route()->parameter('username')]);

        return parent::getValidatorInstance();
    }
}
