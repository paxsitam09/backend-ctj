<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email'    => ['required', 'email', 'min:6'],
            'password' => ['required', 'string', 'min:6'],
        ];
    }
    public function messages()
    {
        return [
            'email.required'    => 'Email is required',
            'email.email'       => 'Invalid email format',
            'email.min'         => 'Email must be at least 6 characters',
            'password.required' => 'Password is required',
            'password.min'      => 'Password must be at least 6 characters',
        ];
    }
}
