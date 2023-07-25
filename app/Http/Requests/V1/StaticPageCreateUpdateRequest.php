<?php

namespace App\Http\Requests\V1;

use App\Traits\CommonTrait;
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
            'title' => 'required',
            'content' => 'required'
        ];
        
        return $rules;
    }

    public function messages()
    {
        $messages = [
            'title.required' => __('messages.validation.title'),
            'content.required' => __('messages.validation.content')
        ];
        
        return $messages;
    }
}
