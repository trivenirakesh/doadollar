<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Helpers\CommonHelper;

class CampaignCategoryResource extends JsonResource
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
            'description' => (!empty($this->description)) ? CommonHelper::shortString($this->description) : "",
            'status' => ($this->status == 1 ? 'Active' : 'Deactive'),
            'image' => $this->image,
            'created_at' => CommonHelper::getConvertedDateTime($this->created_at)
        ];
    }
}
