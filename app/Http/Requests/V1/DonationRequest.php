<?php

namespace App\Http\Requests\V1;

use App\Helpers\CommonHelper;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class DonationRequest extends FormRequest
{

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
            'campaign_id' => [
                'required',
                Rule::exists('campaigns','id')->where(function ($query) {
                    $query->where('status', CommonHelper::getConfigValue('status.active'));
                }),
            ],
            'payment_type_id' => [
                'required',
                Rule::exists('payment_gateway_settings','id')->where(function ($query) {
                    $query->where('status', CommonHelper::getConfigValue('status.active'));
                }),
            ],
            'donation_amount' => 'required|numeric',
        ];

        if(request()->has('entity_id')){
            $rules['entity_id'] = [
                'required',
                Rule::exists('entitymst','id'),
            ];
        }
        return $rules;
    }

    public function messages()
    {
        $messages = [
            'campaign_id.required' => __('messages.donation.campaign_id'),
            'campaign_id.exists' => 'Campaign'.__('messages.validation.not_found'),
            'payment_type_id.required' => __('messages.donation.payment_type_id'),
            'payment_type_id.exists' => 'Payment type'.__('messages.validation.not_found'),
            'donation_amount.required' => __('messages.donation.donation_amount'),
            'donation_amount.numeric' => 'Donation amount' . __('messages.validation.must_numeric'),
        ];
        if(request()->has('entity_id')){
            $messages['entity_id.required'] = __('messages.donation.entity_id');
            $messages['entity_id.exists'] = 'User'.__('messages.validation.not_found');
        }

        return $messages;
    }
}
