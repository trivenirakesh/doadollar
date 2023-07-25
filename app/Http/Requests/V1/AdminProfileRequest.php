<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class AdminProfileRequest extends FormRequest
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
        $rules = [
            'first_name' => 'required|max:50',
            'last_name' => 'required|max:50',
            'mobile' => 'required|numeric|digits:10|unique:entitymst,mobile,' . $this->id . ',id,deleted_at,NULL',
            'email' => 'required|email|unique:entitymst,email,' . $this->id . ',id,deleted_at,NULL'
        ];
        return $rules;
    }

    public function messages()
    {
        $messages = [
            'first_name.required' => __('messages.validation.first_name'),
            'last_name.required' => __('messages.validation.last_name'),
            'email.required' => __('messages.validation.email'),
            'email.email' => __('messages.validation.email_email'),
            'email.unique' => __('messages.validation.email_unique'),
            'mobile.required' => __('messages.validation.mobile'),
            'mobile.numeric' => 'Mobile' . __('messages.validation.must_numeric'),
            'mobile.digits' => __('messages.validation.mobile_digits'),
            'mobile.unique' => __('messages.validation.mobile_unique')
        ];

        return $messages;
    }
}
