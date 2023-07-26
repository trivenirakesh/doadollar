<?php

namespace App\Http\Resources\V1;

use App\Helpers\CommonHelper;
use Illuminate\Http\Resources\Json\JsonResource;

class DonationResource extends JsonResource
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
            'campaign_name' => (!empty($this->campaign_name)) ? $this->campaign_name : '',
            'payment_type_name' => (!empty($this->payment_type_name)) ? $this->payment_type_name : '',
            'entity_first_name' => (!empty($this->entity_first_name)) ? $this->entity_first_name : "",
            'entity_last_name' => (!empty($this->entity_last_name)) ? $this->entity_last_name : "",
            'entity_email' => (!empty($this->entity_email)) ? $this->entity_email : "",
            'entity_mobile' => (!empty($this->entity_mobile)) ? $this->entity_mobile : "",
            'donation_amount' => (!empty($this->donation_amount)) ? $this->donation_amount : '',
            'tip' => (!empty($this->tip)) ? $this->tip : '',
            'transaction_id' => $this->transaction_id,
            'transaction_status' => $this->transaction_status,
            'created_at' => (!empty($this->created_at)) ? CommonHelper::getConvertedDateTime($this->created_at) : ''
        ];
    }
}
