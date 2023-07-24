<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Helpers\CommonHelper;

class EmailTemplateResource extends JsonResource
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
            'title' => $this->title,
            'subject' => $this->subject,
            'message' => (!empty($this->message)) ? CommonHelper::shortString($this->message) : "",
            'status' => ($this->status == 1 ? 'Active' : 'Deactive'),
            'created_at' => CommonHelper::getConvertedDateTime($this->created_at)
        ];
    }
}
