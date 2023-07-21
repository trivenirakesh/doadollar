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
        $rules = [];
        if (request()->has('name')) {
            $rules['name'] = 'required|max:255';
        }
        if (request()->has('type')) {
            $rules['type'] = 'required|numeric|lte:1';
        }
        if (request()->has('status')) {
            $rules['status'] = 'required|numeric|lte:1';
        }

        return $rules;
    }

    public function messages()
    {
        $messages = [];
        if (request()->has('name')) {
            $messages['name.required'] = __('messages.validation.name');
            $messages['name.max'] = __('messages.validation.max_name');
        }
        if (request()->has('type')) {
            $messages['type.required'] = __('messages.validation.type');
            $messages['type.numeric'] = 'Type'.__('messages.validation.must_numeric');
            $messages['type.lte'] = __('messages.validation.type_lte');
        }
        if (request()->has('status')) {
            $messages['status.required'] = __('messages.validation.status');
            $messages['status.numeric'] = 'Status' . __('messages.validation.must_numeric');
            $messages['status.lte'] = __('messages.validation.status_lte');
        }
        return $messages;
    }
}
