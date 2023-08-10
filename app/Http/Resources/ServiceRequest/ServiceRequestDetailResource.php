<?php

namespace App\Http\Resources\ServiceRequest;

use Illuminate\Http\Resources\Json\JsonResource;

class ServiceRequestDetailResource extends JsonResource
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
            'id' =>$this->id,
            'job_card_id' =>$this->job_card_id,
            'user_id' =>$this->user_id,
        ];
    }
}
