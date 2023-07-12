<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Helpers\CommonHelper;

class PaymentGatewaySettingResource extends JsonResource
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
            'status' => ($this->status == 1 ? 'Active' : 'Deactive'),
            'thumb_image' => (!empty($this->file_name)) ? CommonHelper::getImageUrl($this->file_name,$this->path,1) : "",
            'image' => (!empty($this->file_name)) ? CommonHelper::getImageUrl($this->file_name,$this->path,0) : "",
            'created_at' => CommonHelper::getConvertedDateTime($this->created_at)
        ];
    }
}
