<?php

namespace App\Http\Requests\V1;

use App\Traits\CommonTrait;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StaticPageCreateUpdateRequest extends FormRequest
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
            'title' => [
                'required',
                Rule::in('about_us','privacy_policy','terms_and_condition'),
            ],
            'content' => 'required'
        ];
        
        return $rules;
    }

    public function messages()
    {
        $messages = [
            'title.required' => __('messages.validation.title'),
            'title.in' => __('messages.validation.title_invalid'),
            'content.required' => __('messages.validation.content')
        ];
        
        return $messages;
    }
}
