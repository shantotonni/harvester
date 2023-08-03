<?php

namespace App\Http\Resources\Showroom;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ShowroomCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'data'=>$this->collection->transform(function ($showroom){
                return [
                    'id' => $showroom->showroom_id,
                    'owner_name' => $showroom->owner_name,
                    'showroom_name' => $showroom->showroom_name,
                    'address' => $showroom->address,
                    'mobile' => $showroom->mobile,
                    'lat' => $showroom->lat,
                    'long' => $showroom->long
                ];
            })
        ];
    }
}
