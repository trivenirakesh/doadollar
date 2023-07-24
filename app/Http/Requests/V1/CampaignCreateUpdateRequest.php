<?php

namespace App\Http\Requests\V1;

use App\Helpers\CommonHelper;
use App\Traits\CommonTrait;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CampaignCreateUpdateRequest extends FormRequest
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
            'campaign_category_id' =>[
                'required',
                Rule::exists('campaign_categories','id')->where(function ($query) {
                    $query->where('status', CommonHelper::getConfigValue('status.active'));
                })
            ],
            'start_datetime' => 'required',
            'end_datetime' => 'required',
            'donation_target' => 'required',
        ];
        if (request()->hasFile('image')) {
            $rules['image'] = 'required|max:2048|mimes:jpg,png,jpeg';
        }
        if ($this->id != null) {
            $rules['unique_code'] = 'required|max:200|unique:campaigns,unique_code,' . $this->id . ',id,deleted_at,NULL';
        } else {
            $rules['unique_code'] = 'required|max:200|unique:campaigns,unique_code,NULL,id,deleted_at,NULL';
        }
        return $rules;
    }

    public function messages()
    {
        $messages = [];
        $messages = [
            'name.required' => __('messages.validation.name'),
            'name.max' => __('messages.validation.max'),
            'campaign_category_id.required' => __('messages.validation.campaign_category_id'),
            'campaign_category_id.exists' => 'Campaign category'.__('messages.validation.not_found'),
            'start_datetime.required' => __('messages.validation.start_datetime'),
            'end_datetime.required' => __('messages.validation.end_datetime'),
            'donation_target.required' => __('messages.validation.donation_target'),
            'donation_target.numeric' => 'Donation target' . __('messages.validation.must_numeric'),
            'unique_code.required' => __('messages.validation.unique_code'),
            'unique_code.max' => __('messages.validation.max'),
            'unique_code.unique' => __('messages.validation.unique_code_unique'),
        ];

        if (request()->hasFile('image')) {
            $messages['image.required'] = __('messages.validation.image');
            $messages['image.max'] =  __('messages.validation.image-max');
            $messages['image.mimes'] = __('messages.validation.image-mimes');
        }

        if (request()->has('status')) {
            $messages['status.required'] = __('messages.validation.status');
            $messages['status.numeric'] = 'Status' . __('messages.validation.must_numeric');
            $messages['status.lte'] = __('messages.validation.status_lte');
        }
        return $messages;
    }
}
