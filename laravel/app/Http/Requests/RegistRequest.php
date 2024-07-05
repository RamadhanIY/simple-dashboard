<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class RegistRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name'=>'required|unique:users',
            'email' =>'required|email:rfc,dns|unique:users,email',
            'password' => [
                'required',
                Password::min(12)
            ],
            'password_confirmation' => 'required|same:password'
        ];
    }

    public function messages()
    {
        return [
            'name.required'=> 'Please fill your Full Name',
            'name.unique' => 'This name is already taken',
            'email.required' => 'Email is required',
            'email.email' => 'Please enter a valid email address',
            'email.unique' => 'This email address is already taken',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 12 characters long',
            'password_confirmation.same' => 'Password confirmation must match the password',
        ];
    }

}
