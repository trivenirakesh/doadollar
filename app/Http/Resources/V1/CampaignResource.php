<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Helpers\CommonHelper;

class CampaignResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'unique_code' => url($this->unique_code),
            'description' => (!empty($this->description)) ? CommonHelper::shortString($this->description) : "",
            'campaign_category' => (!empty($this->campaign_category_name)) ? $this->campaign_category_name : '',
            'status' => ($this->status == 1 ? 'Active' : 'Deactive'),
            'campaign_status' => '',
            'campaign_start_datetime' => CommonHelper::getConvertedDateTime($this->start_datetime,'d-m-Y | H:i'),
            'campaign_end_datetime' => CommonHelper::getConvertedDateTime($this->end_datetime,'d-m-Y | H:i'),
            'donation_target' => $this->donation_target,
            'created_at' => CommonHelper::getConvertedDateTime($this->created_at)
        ];
    }
}
