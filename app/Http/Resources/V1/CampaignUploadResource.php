<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Helpers\CommonHelper;

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
            'upload_type_name' => isset($this->uploadType['name']) ? $this->uploadType['name'] : '',
            'title' => $this->title,
            'description' => $this->description,
            'file_name' => (!empty($this->file_name)) ? $this->file_name : "",
            'file_path' => (!empty($this->path)) ? $this->path : "",
            'link' => (!empty($this->link)) ? $this->link : "",
            'created_at' => CommonHelper::getConvertedDateTime($this->created_at)
        ];
    }
}
