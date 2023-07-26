<?php

namespace App\Http\Requests\V1;

use App\Helpers\CommonHelper;
use App\Traits\CommonTrait;
use Illuminate\Validation\Rule;
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
            'first_name' => 'required|max:200',
            'last_name' => 'required|max:200',
            'entity_type' => 'required|digits:1|lte:2',
            'status' => 'required|numeric|lte:1',
            'role_id' => [
                'required',
                Rule::exists('roles','id')->where(function ($query) {
                    $query->where('status', CommonHelper::getConfigValue('status.active'));
                }),
            ]
        ];
        if ($this->id != null) {
            $rules['email'] = 'required|email|unique:entitymst,email,' . $this->id . ',id,deleted_at,NULL';
            $rules['mobile'] = 'required|numeric|digits:10|unique:entitymst,mobile,' . $this->id . ',id,deleted_at,NULL';
        } else {
            $rules['email'] = 'required|email|unique:entitymst,email,NULL,id,deleted_at,NULL';
            $rules['mobile'] = 'required|numeric|digits:10|unique:entitymst,mobile,NULL,id,deleted_at,NULL';
        }
        if (request()->has('password') || request()->has('id') ) {
            $rules['password'] = request()->has('id') && request()->id>0 ? null: [
                'required',
                'min:8',          
                'regex:/[a-z]/',      // must contain at least one lowercase letter
                'regex:/[A-Z]/',      // must contain at least one uppercase letter
                'regex:/[0-9]/',      // must contain at least one digit
                'regex:/[@$!%*#?&]/', // must contain a special character
            ];
        }
        
        return $rules;
    }

    public function messages()
    {
        $messages = [];
        $messages = [
            'first_name.required' => __('messages.validation.first_name'),
            'first_name.max' => __('messages.validation.max'),
            'last_name.required' => __('messages.validation.last_name'),
            'last_name.max' => __('messages.validation.max'),
            'email.required' => __('messages.validation.email'),
            'email.email' => __('messages.validation.email_email'),
            'email.unique' => __('messages.validation.email_unique'),
            'mobile.required' => __('messages.validation.mobile'),
            'mobile.numeric' => 'Mobile' . __('messages.validation.must_numeric'),
            'mobile.digits' => __('messages.validation.mobile_digits'),
            'mobile.unique' => __('messages.validation.mobile_unique'),
            'entity_type.required' => __('messages.validation.entity_type'),
            'entity_type.digits' => __('messages.validation.entity_type_digits'),
            'entity_type.lte' => __('messages.validation.entity_type_lte'),
            'status.required' => __('messages.validation.status'),
            'status.numeric' => 'Status' . __('messages.validation.must_numeric'),
            'status.lte' => __('messages.validation.status_lte'),
            'role_id.required' => __('messages.validation.role_id'),
            'role_id.exists' => 'Role'.__('messages.validation.not_found'),
        ];
        if (request()->has('password')) {
            $messages['password.required'] = __('messages.validation.password');
            $messages['password.min'] = __('messages.validation.new_password_min');
            $messages['password.regex'] = __('messages.validation.strong_password');
        }
        
        return $messages;
    }
}
