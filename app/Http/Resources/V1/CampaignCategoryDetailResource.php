<?php

namespace App\Http\Resources\V1;

use App\Helpers\CommonHelper;
use Illuminate\Http\Resources\Json\JsonResource;

class CampaignCategoryDetailResource extends JsonResource
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
            'description' => (!empty($this->description)) ? $this->description : "",
            'status' => $this->status,
            'status_text' => ($this->status == 1 ? 'Active' : 'Deactive'),
            'image' => $this->image,
            'created_at' => CommonHelper::getConvertedDateTime($this->created_at),
            'updated_at' => CommonHelper::getConvertedDateTime($this->updated_at),
        ];
    }
}
