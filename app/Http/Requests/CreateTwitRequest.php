<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateTwitRequest extends FormRequest
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
            'body' => 'required_without:media|string|max:250',
            'media' => 'required_without:body|file|mimes:jpg,png,mp4,mkv|max:10485760',
            'reply_to' => 'nullable|exists:twits,id',
        ];
    }
}
