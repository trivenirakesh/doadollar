<?php

namespace App\Http\Requests\V1;

use App\Traits\CommonTrait;
use Illuminate\Foundation\Http\FormRequest;

class UsersCreateUpdateRequest extends FormRequest
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
            'first_name' => 'required|max:50',
            'last_name' => 'required|max:50'
        ];
        if ($this->id != null) {
            $rules['email'] = 'required|email|unique:entitymst,email,' . $this->id . ',id,deleted_at,NULL';
            $rules['mobile'] = 'required|numeric|digits:10|unique:entitymst,mobile,' . $this->id . ',id,deleted_at,NULL';
        } else {
            $rules['email'] = 'required|email|unique:entitymst,email,NULL,id,deleted_at,NULL';
            $rules['mobile'] = 'required|numeric|digits:10|unique:entitymst,mobile,NULL,id,deleted_at,NULL';
        }
        if (request()->has('password')) {
            $rules['password'] = 'required';
        }
        if (request()->has('entity_type')) {
            $rules['entity_type'] = 'required|digits:1|lte:2';
        }
        if (request()->has('status')) {
            $rules['status'] = 'required|numeric|lte:1';
        }
        if (request()->has('role_id') && request()->entity_type != 2) {
            $rules['role_id'] = 'required|numeric';
        }
        return $rules;
    }

    public function messages()
    {
        $messages = [];
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
        if (request()->has('entity_type')) {
            $messages['password.required'] = __('messages.validation.password');
        }
        if (request()->has('entity_type')) {
            $messages['entity_type.required'] = __('messages.validation.entity_type');
            $messages['entity_type.digits'] = __('messages.validation.entity_type_digits');
            $messages['entity_type.lte'] = __('messages.validation.entity_type_lte');
        }
        if (request()->has('status')) {
            $messages['status.required'] = __('messages.validation.status');
            $messages['status.numeric'] = 'Status' . __('messages.validation.must_numeric');
            $messages['status.lte'] = __('messages.validation.status_lte');
        }
        if (request()->has('role_id') && request()->entity_type != 2) {
            $messages['role_id.required'] = __('messages.validation.role_id');
            $messages['role_id.numeric'] = 'Role id' . __('messages.validation.must_numeric');
        }

        return $messages;
    }
}
