<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Helpers\CommonHelper;

class EntityDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $entityType = ['Super Admin', 'Manager', 'User'];
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'username' => $this->first_name . ' ' . $this->last_name,
            'email' => (!empty($this->email)) ? $this->email : '',
            'mobile' => (!empty($this->mobile)) ? $this->mobile : '',
            'status' => $this->status,
            'status_text' => ($this->status == 1 ? 'Active' : 'Deactive'),
            'entity_type' => $this->entity_type,
            'entity_type_text' => $entityType[$this->entity_type],
            'role_id' => $this->role_id,
            'role' => isset($this->role['name']) ? $this->role['name'] : '',
            'created_at' => CommonHelper::getConvertedDateTime($this->created_at)
        ];
    }
}
