<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ForgotPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, array<string>> */
    public function rules(): array
    {
        return [
            'login' => ['required', 'string'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'password_confirmation' => ['required', 'string'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'login.required' => __('validation_login_required'),
            'email.required' => __('validation_email_required'),
            'email.email' => __('validation_email_invalid'),
            'password.required' => __('validation_password_required'),
            'password.min' => __('validation_password_min'),
            'password.confirmed' => __('validation_password_confirmed'),
        ];
    }
}
