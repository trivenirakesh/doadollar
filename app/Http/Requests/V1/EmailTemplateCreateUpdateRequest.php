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
        $rules =  [
            'title' => 'required|max:200',
            'subject' => 'required|max:200',
            'message' => 'required',
            'status' => 'required|numeric|lte:1'
        ];
        
        return $rules;
    }

    public function messages()
    {
        $messages = [
            'title.required' => __('messages.validation.title'),
            'title.max' => __('messages.validation.max'),
            'subject.required' => __('messages.validation.subject'),
            'subject.max' =>  __('messages.validation.max'),
            'message.required' => __('messages.validation.message'),
            'status.required' => __('messages.validation.status'),
            'status.numeric' => 'Status' . __('messages.validation.must_numeric'),
            'status.lte' => __('messages.validation.status_lte'),
        ];
        
        return $messages;
    }
}
