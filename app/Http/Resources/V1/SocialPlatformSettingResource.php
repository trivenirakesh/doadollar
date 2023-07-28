<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Helpers\CommonHelper;

class SocialPlatformSettingResource extends JsonResource
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
            'api_key' => (!empty($this->api_key)) ? $this->api_key : "",
            'secret_key' => (!empty($this->secret_key)) ? $this->secret_key : "",
            'status' => $this->status,
            'status_text' => ($this->status == 1 ? 'Active' : 'Deactive'),
            'image' => $this->image,
            'created_at' => CommonHelper::getConvertedDateTime($this->created_at),
            'updated_at' => CommonHelper::getConvertedDateTime($this->updated_at),
        ];
    }
}
