<?php

namespace App\Http\Requests\V1;

use App\Traits\CommonTrait;
use Illuminate\Foundation\Http\FormRequest;

class UploadTypeCreateUpdateRequest extends FormRequest
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
        $rules = [
            'name' => 'required|max:200',
            'type' => 'required|numeric|lte:1',
            'status' => 'required|numeric|lte:1',
        ];
        
        return $rules;
    }

    public function messages()
    {
        $messages = [
            'name.required' => __('messages.validation.name'),
            'name.max' => __('messages.validation.max_name'),
            'type.required' => __('messages.validation.type'),
            'type.numeric' => 'Type'.__('messages.validation.must_numeric'),
            'type.lte' => __('messages.validation.type_lte'),
            'status.required' => __('messages.validation.status'),
            'status.numeric' => 'Status' . __('messages.validation.must_numeric'),
            'status.lte' => __('messages.validation.status_lte'),
        ];
        
        return $messages;
    }
}
