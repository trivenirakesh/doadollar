<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ChangePasswordRequest extends FormRequest
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
        $user = auth()->user();
        $rules = [
            'old_password' => ['required', function ($attribute, $value, $fail) use ($user) {
                if (!Hash::check($value, $user->password)) {
                    $fail(__('messages.validation.old_password_incorrect'));
                }
            }],
            'password' => ['required', 'min:8', 'string', 'regex:/[a-z]/', 'regex:/[A-Z]/', 'regex:/[0-9]/', 'regex:/[@$!%*#?&]/'],
            'password_confirmation' => ['required', 'same:password'],
        ];
        return $rules;
    }

    public function messages()
    {
        $messages = [
            'old_password.required' => __('messages.validation.old_password'),
            'password.required' => __('messages.validation.new_password'),
            'password.min' => __('messages.validation.new_password_min'),
            'password.regex' => __('messages.validation.strong_password'),
            'password_confirmation.required' => __('messages.validation.password_confirmation'),
            'password_confirmation.same' => __('messages.validation.password_confirmation_same'),
        ];

        return $messages;
    }
}
