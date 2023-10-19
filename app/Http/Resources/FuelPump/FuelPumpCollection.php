<?php

namespace App\Http\Resources\FuelPump;

use Illuminate\Http\Resources\Json\ResourceCollection;

class FuelPumpCollection extends ResourceCollection
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
            'data'=>$this->collection->transform(function ($fuel){
                return [
                    'id' => $fuel->fuel_pump_id,
                    'district_id' => $fuel->district_id,
                    'district_name' => isset($fuel->District) ? $fuel->District->name : '',
                    'owner_name' => $fuel->owner_name,
                    'fuel_pump_name' => $fuel->fuel_pump_name,
                    'address' => $fuel->address,
                    'mobile' => $fuel->mobile,
                    'lat' => $fuel->lat,
                    'lon' => $fuel->lon,
                    'image' => $fuel->image,
                    'mobile_image' => url('/').'/images/fuel/'. $fuel->image
                ];
            })
        ];
    }
}
