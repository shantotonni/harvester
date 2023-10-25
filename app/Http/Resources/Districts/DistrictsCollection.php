<?php

namespace App\Http\Resources\Districts;

use Illuminate\Http\Resources\Json\ResourceCollection;

class DistrictsCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data'=>$this->collection->transform(function ($district){
                return[
                  'district_id'  => $district->id,
                'district_name'=>$district->name,
                'district_name_bn'=>$district->name_bn

                ];
            })
        ];
    }
}
