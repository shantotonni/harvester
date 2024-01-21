<?php

namespace App\Http\Resources\Dealer;

use Illuminate\Http\Resources\Json\ResourceCollection;

class DealerCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data'=>$this->collection->transform(function ($dealer){
                return [
                    'id' => $dealer->id,
                    'area_id' => $dealer->area_id,
                    'area_name' => isset($dealer->Area) ? $dealer->Area->areaname : '',
                    'area_name_bn' => isset($dealer->Area) ? $dealer->Area->name_bn : '',
                    'dealer_name' => $dealer->responsible_person,
                    'dealer_code' => $dealer->dealer_code,
                    'store_name' => $dealer->store_name,
                    'dealer_type' => $dealer->dealer_type,
                    'address' => $dealer->address,
                    'mobile' => $dealer->mobile,
                    'lat' => $dealer->lat,
                    'long' => $dealer->lon,
                    'image' => $dealer->image,
                    'mobile_image' => url('/').'/images/dealer/'. $dealer->image

                ];
            })
        ];
    }
}
