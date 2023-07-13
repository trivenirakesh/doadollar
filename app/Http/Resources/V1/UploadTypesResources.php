<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Helpers\CommonHelper;

class UploadTypesResources extends JsonResource
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
            'type' => ($this->type == 1 ? 'Links' : 'Upload'),
            'status' => ($this->status == 1 ? 'Active' : 'Deactive'),
            'created_at' => CommonHelper::getConvertedDateTime($this->created_at)
        ];
    }
}
