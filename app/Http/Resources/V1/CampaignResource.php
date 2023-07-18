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
            'unique_code' => $this->unique_code,
            'description' => (!empty($this->description)) ? $this->description : "",
            'campaign_category' => $this->campaign_category_id,
            'status' => ($this->status == 1 ? 'Active' : 'Deactive'),
            'campaign_start_datetime' => CommonHelper::getConvertedDateTime($this->start_datetime),
            'campaign_end_datetime' => CommonHelper::getConvertedDateTime($this->end_datetime),
            'donation_target' => $this->donation_target,
            'qr_code' => (!empty($this->qr_image)) ? CommonHelper::getImageUrl($this->qr_image,$this->qr_path,0) : "",
            'thumb_image' => (!empty($this->cover_image)) ? CommonHelper::getImageUrl($this->cover_image,$this->cover_image_path,1) : "",
            'image' => (!empty($this->cover_image)) ? CommonHelper::getImageUrl($this->cover_image,$this->cover_image_path,0) : "",
            'created_at' => CommonHelper::getConvertedDateTime($this->created_at)
        ];
    }
}
