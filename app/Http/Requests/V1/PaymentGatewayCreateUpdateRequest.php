<?php

namespace App\Http\Requests\V1;

use App\Traits\CommonTrait;
use Illuminate\Foundation\Http\FormRequest;

class PaymentGatewayCreateUpdateRequest extends FormRequest
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
            'api_key' => 'required',
            'secret_key' => 'required',
            'status' => 'required|numeric|lte:1',
        ];
        if (request()->hasFile('image')) {
            $rules['image'] = 'required|max:2048|mimes:jpg,png,jpeg';
        }
        return $rules;
    }

    public function messages()
    {
        $messages = [
            'name.required' => __('messages.validation.name'),
            'name.max' => __('messages.validation.max_name'),
            'api_key.required' => __('messages.validation.api_key'),
            'secret_key.required' => __('messages.validation.secret_key'),
            'status.required' => __('messages.validation.status'),
            'status.numeric' => 'Status' . __('messages.validation.must_numeric'),
            'status.lte' => __('messages.validation.status_lte'),
        ];
        
        if (request()->hasFile('image')) {
            $messages['image.required'] = __('messages.validation.image');
            $messages['image.max'] =  __('messages.validation.image-max');
            $messages['image.mimes'] = __('messages.validation.image-mimes');
        }
        
        return $messages;
    }
}
