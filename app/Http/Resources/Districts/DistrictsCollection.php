<?php

namespace App\Http\Resources\Districts;

use Illuminate\Http\Resources\Json\ResourceCollection;

class DistrictsCollection extends ResourceCollection
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
            'data'=>$this->collection->transform(function ($district){
                return[
                  'district_id'  => $district->id,
                'district_name'=>$district->name

                ];
            })
        ];
    }
}
