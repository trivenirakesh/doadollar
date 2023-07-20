<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Helpers\CommonHelper;

class InquiryResource extends JsonResource
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
            'username' => $this->first_name.' '.$this->last_name,
            'email' => (!empty($this->email)) ? $this->email : '',
            'mobile' => (!empty($this->mobile)) ? $this->mobile : '',
            'message' => (!empty($this->message)) ? $this->message : '',
            'created_at' => CommonHelper::getConvertedDateTime($this->created_at)
        ];
    }
}
