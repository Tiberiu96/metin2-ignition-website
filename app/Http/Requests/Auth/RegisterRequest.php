<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, array<string>> */
    public function rules(): array
    {
        return [
            'login' => ['required', 'string', 'min:4', 'max:30', 'unique:account.account,login'],
            'email' => ['required', 'email', 'max:100', 'unique:account.account,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'social_id' => ['required', 'digits:7'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'login.required' => __('validation_login_required'),
            'login.min' => __('validation_login_min'),
            'login.max' => __('validation_login_max'),
            'login.unique' => __('validation_login_unique'),
            'email.required' => __('validation_email_required'),
            'email.email' => __('validation_email_invalid'),
            'email.unique' => __('validation_email_unique'),
            'password.required' => __('validation_password_required'),
            'password.min' => __('validation_password_min'),
            'password.confirmed' => __('validation_password_confirmed'),
            'social_id.required' => __('validation_securitycode_required'),
            'social_id.digits' => __('validation_securitycode_digits'),
        ];
    }
}
