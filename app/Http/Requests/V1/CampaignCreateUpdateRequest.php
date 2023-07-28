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
            'campaign_category_id' => [
                'required',
                Rule::exists('campaign_categories', 'id')->where(function ($query) {
                    $query->where('status', CommonHelper::getConfigValue('status.active'));
                })
            ],
            'start_datetime' => 'required|date_format:Y-m-d H:i:s|before_or_equal:end_datetime',
            'end_datetime' => 'required|date_format:Y-m-d H:i:s|after_or_equal:start_datetime',
            'donation_target' => ['required', 'numeric', 'between:0,999999.99'],
            'status' => 'required|numeric|in:0,1',

            'files_uplaod' => 'nullable|array',
            'files_uplaod.*.title' => 'required|max:255',
            'files_uplaod.*.description' => 'nullable|max:2500',
            // 'files_uplaod.*.file' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'files_uplaod.*.image' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if (is_file($value)) {
                        // Validate file extension and size
                        $allowedExtensions = ['jpg', 'jpeg', 'png'];
                        $extension = strtolower($value->getClientOriginalExtension());
                        $fileSizeKB = $value->getSize() / 1024;
                        if (!in_array($extension, $allowedExtensions) || $fileSizeKB > 2048) {
                            $fail(__('messages.campaigns.files_uplaod_file_image_invalid_file'));
                        }
                    } else {
                        // Check if the value is a valid URL
                        if (!filter_var($value, FILTER_VALIDATE_URL)) {
                            $fail(__('messages.campaigns.files_uplaod_file_image_invalid'));
                            $fail("The image must be a valid input.");
                        }
                    }
                },
            ],

            'video' => 'nullable|array',
            'video.*.title' => 'required|max:255',
            'video.*.description' => 'nullable|max:2500',
            'video.*.link' => ['required', 'regex:/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i', 'max:255']
        ];
        if (request()->hasFile('image')) {
            $rules['image'] = 'required|max:2048|mimes:jpg,png,jpeg';
        }
        if ($this->id != null) {
            $rules['unique_code'] = 'required|max:200|alpha_dash|unique:campaigns,unique_code,' . $this->id . ',id,deleted_at,NULL';
        } else {
            $rules['unique_code'] = 'required|max:200|alpha_dash|unique:campaigns,unique_code,NULL,id,deleted_at,NULL';
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
            'campaign_category_id.exists' => 'Campaign category' . __('messages.validation.not_found'),
            'start_datetime.required' => __('messages.validation.start_datetime'),
            'end_datetime.required' => __('messages.validation.end_datetime'),
            'donation_target.required' => __('messages.validation.donation_target'),
            'donation_target.numeric' => 'Donation target' . __('messages.validation.must_numeric'),
            'donation_target.between' =>   __('messages.validation.donation_target_between'),
            'unique_code.required' => __('messages.validation.unique_code'),
            'unique_code.max' => __('messages.validation.max'),
            'unique_code.unique' => __('messages.validation.unique_code_unique'),
            'unique_code.alpha_dash' => __('messages.validation.unique_code_alpha_dash'),
            'status.required' => __('messages.validation.status'),
            'status.numeric' => 'Status' . __('messages.validation.must_numeric'),
            'status.in' => __('messages.validation.status_in'),

            'files_uplaod.array' => __('messages.campaigns.files_uplaod_array'),
            'files_uplaod.*.title.required' =>  __('messages.campaigns.files_uplaod_title_required'),
            'files_uplaod.*.title.max' =>  __('messages.campaigns.files_uplaod_title_max'),
            'files_uplaod.*.description.max' =>  __('messages.campaigns.files_uplaod_description_max'),
            'files_uplaod.*.file.required' => __('messages.campaigns.files_uplaod_file_required'),
            'files_uplaod.*.file.image' =>  __('messages.campaigns.files_uplaod_file_image'),
            'files_uplaod.*.file.mimes' =>  __('messages.campaigns.files_uplaod_file_mimes'),
            'files_uplaod.*.file.max' =>  __('messages.campaigns.files_uplaod_file_max'),

            // 'video.required' => 'Please select at least one video.',
            'video.array' =>  __('messages.campaigns.files_array'),
            'video.*.title.required' =>  __('messages.campaigns.video_title_required'),
            'video.*.title.max' =>  __('messages.campaigns.video_title_max'),
            'video.*.description.max' =>  __('messages.campaigns.video_description_max'),
            'video.*.link.required' => __('messages.campaigns.video_link_required'),
            'video.*.link.regex' =>  __('messages.campaigns.video_link_regex'),
            'video.*.link.max' => __('messages.campaigns.video_url_max'),

            'start_datetime.required' =>  __('messages.campaigns.start_datetime_required'),
            'start_datetime.date_format' =>  __('messages.campaigns.start_datetime_date_format'),
            'start_datetime.before_or_equal' =>  __('messages.campaigns.start_datetime_before_or_equal'),
            'end_datetime.required' =>  __('messages.campaigns.end_datetime_required'),
            'end_datetime.date_format' => __('messages.campaigns.end_datetime_date_format'),
            'end_datetime.after_or_equal' =>  __('messages.campaigns.end_datetime_after_or_equal'),
        ];

        if (request()->hasFile('image')) {
            $messages['image.required'] = __('messages.validation.image');
            $messages['image.max'] =  __('messages.validation.image-max');
            $messages['image.mimes'] = __('messages.validation.image-mimes');
        }

        return $messages;
    }
}
