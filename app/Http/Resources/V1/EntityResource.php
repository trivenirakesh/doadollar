<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Helpers\CommonHelper;

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
            'username' => $this->first_name.' '.$this->last_name,
            'email' => (!empty($this->email)) ? $this->email : '',
            'mobile' => (!empty($this->mobile)) ? $this->mobile : '',
            'status' => ($this->status == 1 ? 'Active' : 'Deactive'),
            'entity_type' => $this->getEntityName($this->entity_type),
            'role' => isset($this->role['name']) ? $this->role['name'] : '',
            'created_at' => CommonHelper::getConvertedDateTime($this->created_at)
        ];
    }

    public function getEntityName($type){
        $str = '';
        if($type == 0){
            $str = 'Super Admin';
        }elseif($type == 1){
            $str = 'Manager';
        }elseif($type == 2){
            $str = 'User';
        }
        return $str;
    }   
}
