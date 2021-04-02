<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UnmarkRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return !is_null($this->route('twit')->bookmarks()->where('user_id', $this->user()->id)->first());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
