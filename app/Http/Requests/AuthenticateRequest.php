<?php

namespace App\Http\Requests;

use App\Rules\MobileRule;
use Illuminate\Foundation\Http\FormRequest;

class AuthenticateRequest extends FormRequest
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
            'mobile' => ['required_without:email', new MobileRule()],
            'email' => 'required_without:mobile|email:rfc',
            'code' => 'required|numeric|digits:6'
        ];
    }
}
