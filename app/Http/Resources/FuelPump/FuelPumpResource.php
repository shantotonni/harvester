<?php

namespace App\Http\Resources\FuelPump;

use Illuminate\Http\Resources\Json\JsonResource;

class FuelPumpResource extends JsonResource
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
            'id' => $this->fuel_pump_id,
            'district_id' => $this->district_id,
            'district_name' => isset($this->District) ? $this->District->name : '',
            'owner_name' => $this->owner_name,
            'fuel_pump_name' => $this->fuel_pump_name,
            'address' => $this->address,
            'mobile' => $this->mobile,
            'lat' => $this->lat,
            'lon' => $this->lon,
            'image' => $this->image,
            'mobile_image' => url('/').'/images/fuel/'. $this->image

        ];
    }
}
