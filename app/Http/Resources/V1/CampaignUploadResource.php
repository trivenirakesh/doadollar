<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Helpers\CommonHelper;
use App\Models\CampaignUploads;

class CampaignUploadResource extends JsonResource
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
            'campaign_id' => $this->campaign_id,
            'upload_type' => $this->upload_type,
            'title' => $this->title,
            'description' => (!empty($this->description)) ? CommonHelper::shortString($this->description) : "",
            'thumb_image' => (!empty($this->image)) ? CommonHelper::getImageUrl($this->image,CampaignUploads::FOLDERNAME.'thumb/',0) : "",
            'image' => (!empty($this->image)) ? CommonHelper::getImageUrl($this->image,CampaignUploads::FOLDERNAME,0) : "",
            'link' => (!empty($this->link)) ? $this->link : "",
            'created_at' => CommonHelper::getConvertedDateTime($this->created_at)
        ];
    }
}
