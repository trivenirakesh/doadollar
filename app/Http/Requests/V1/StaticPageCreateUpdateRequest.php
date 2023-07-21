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
        $rules =  [];
        if (request()->has('title')) {
            $rules['title'] = 'required';
        }
        if (request()->hasFile('content')) {
            $rules['content'] = 'required';
        }
        return $rules;
    }

    public function messages()
    {
        $messages = [];
        if (request()->has('title')) {
            $messages['title.required'] = __('messages.validation.title');
        }
        if (request()->hasFile('content')) {
            $messages['content.required'] = __('messages.validation.content');
        }

        return $messages;
    }
}
