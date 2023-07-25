<?php

namespace App\Http\Requests\V1;

use App\Traits\CommonTrait;
use Illuminate\Foundation\Http\FormRequest;

class InquiryCreateUpdateRequest extends FormRequest
{

    use CommonTrait;
    // protected function failedValidation(Validator $validator)
    // {
    //     throw new ValidationException($validator);
    // }

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
        $rules =  [
            'first_name' => 'required|max:200',
            'last_name' => 'required|max:200',
            'email' => 'required|email',
            'mobile' => 'required|numeric|digits:10',
        ];
        
        return $rules;
    }

    public function messages()
    {
        $messages = [
            'first_name.required' => __('messages.validation.first_name'),
            'first_name.max' => __('messages.validation.max'),
            'last_name.required' => __('messages.validation.last_name'),
            'last_name.max' => __('messages.validation.max'),
            'email.required' => __('messages.validation.email'),
            'email.email' => __('messages.validation.email_email'),
            'mobile.required' => __('messages.validation.mobile'),
            'mobile.numeric' => 'Mobile'.__('messages.validation.must_numeric'),
            'mobile.digits' => __('messages.validation.mobile_digits'),
        ];
        
        return $messages;
    }
}
