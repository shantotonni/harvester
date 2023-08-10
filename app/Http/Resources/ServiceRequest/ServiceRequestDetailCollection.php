<?php

namespace App\Http\Resources\ServiceRequest;

use Illuminate\Http\Resources\Json\ResourceCollection;
use function Symfony\Component\String\s;

class ServiceRequestDetailCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection->transform(function ($service_request_detail) {
                return [
                    'id' =>$service_request_detail->id,
                    'job_card_id' =>$service_request_detail->job_card_id,
                    'user_id' =>$service_request_detail->user_id,
                ];
            })
        ];
    }
}
