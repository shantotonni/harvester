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
            'data' => $this->collection->transform(function ($job_card_detail) {
                return [
                    'id' =>$job_card_detail->id,
                    'job_card_id' =>$job_card_detail->job_card_id,
                    'user_id' =>$job_card_detail->user_id,
                ];
            })
        ];
    }
}
