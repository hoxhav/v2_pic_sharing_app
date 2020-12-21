<?php

namespace App\Http\Requests\auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterAuthRequest extends FormRequest
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
            'name' => 'required|string|between:2,255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|between:6,255',
            'password_confirmation' => 'required|string|between:6,255',
        ];
    }
}
