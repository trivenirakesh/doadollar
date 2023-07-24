<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Helpers\CommonHelper;
use App\Http\Resources\V1\CampaignUploadResource;
use App\Models\Campaign;

class CampaignDetailResource extends JsonResource
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
            'campaign_url' => url($this->unique_code),
            'description' => (!empty($this->description)) ? CommonHelper::shortString($this->description) : "",
            'campaign_category' => $this->campaign_category_id,
            'status' => ($this->status == 1 ? 'Active' : 'Deactive'),
            'campaign_date' => CommonHelper::getConvertedDateTime($this->start_datetime,'dS F Y').' To '.CommonHelper::getConvertedDateTime($this->end_datetime,'dS F Y'),
            'donation_target' => $this->donation_target,
            'qr_code' => (!empty($this->qr_image)) ? CommonHelper::getImageUrl($this->qr_image,Campaign::FOLDERNAME,0) : "",
            'thumb_image' => (!empty($this->image)) ? CommonHelper::getImageUrl($this->image,Campaign::FOLDERNAME.'thumb/',0) : "",
            'image' => (!empty($this->image)) ? CommonHelper::getImageUrl($this->image,Campaign::FOLDERNAME,0) : "",
            'created_at' => CommonHelper::getConvertedDateTime($this->created_at),
            'uploads' => CampaignUploadResource::collection($this->uploads),
        ];
    }
}
