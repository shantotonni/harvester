<?php

namespace App\Http\Resources\ServiceCenter;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ServiceCenterCollection extends ResourceCollection
{

    public function toArray($request)
    {
        return [
            'data'=>$this->collection->transform(function ($service_center){
                return [
                    'id' => $service_center->id,
                    'area_id' => $service_center->area_id,
                    'areaname'=>isset($service_center->area) ? $service_center->area->name:'',
                    'address' => $service_center->address,
                    'responsible_person' => $service_center->responsible_person,
                    'mobile' => $service_center->mobile,
                    'lat' => $service_center->lat,
                    'lon' => $service_center->lon,
                    'image' => $service_center->image
                ];
            })
        ];

    }
}
