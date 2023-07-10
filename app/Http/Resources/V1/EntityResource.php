<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class EntityResource extends JsonResource
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
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => (!empty($this->email)) ? $this->email : '',
            'mobile' => (!empty($this->mobile)) ? $this->mobile : '',
            'status' => ($this->status == 1 ? 'Active' : 'Deactive'),
            'created_at' => date('d-m-Y h:i A',strtotime($this->created_at))
        ];
    }
}
