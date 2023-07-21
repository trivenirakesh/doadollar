<?php

namespace App\Http\Requests\V1;

use App\Traits\CommonTrait;
use Illuminate\Foundation\Http\FormRequest;

class EmailTemplateCreateUpdateRequest extends FormRequest
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
            $rules['title'] = 'required|max:200';
        }
        if (request()->hasFile('subject')) {
            $rules['subject'] = 'required|max:200';
        }
        if (request()->hasFile('message')) {
            $rules['message'] = 'required';
        }
        if (request()->has('status')) {
            $rules['status'] = 'required|numeric|lte:1';
        }

        return $rules;
    }

    public function messages()
    {
        $messages = [];
        if (request()->has('title')) {
            $messages['title.required'] = __('messages.validation.title');
            $messages['title.max'] = __('messages.validation.max');
        }
        if (request()->hasFile('subject')) {
            $messages['subject.required'] = __('messages.validation.subject');
            $messages['subject.max'] =  __('messages.validation.max');
        }

        if (request()->hasFile('message')) {
            $messages['message.required'] = __('messages.validation.message');
        }

        if (request()->has('status')) {
            $messages['status.required'] = __('messages.validation.status');
            $messages['status.numeric'] = 'Status' . __('messages.validation.must_numeric');
            $messages['status.lte'] = __('messages.validation.status_lte');
        }
        return $messages;
    }
}
