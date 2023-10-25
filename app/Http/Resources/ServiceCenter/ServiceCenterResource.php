<?php

namespace App\Http\Resources\ServiceCenter;

use Illuminate\Http\Resources\Json\JsonResource;

class ServiceCenterResource extends JsonResource
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
            'id' => $this->id,
            'area_id' => $this->area_id,
            'areaname'=>isset($this->area) ? $this->area->name:'',
            'address' => $this->address,
            'responsible_person' => $this->responsible_person,
            'mobile' => $this->mobile,
            'lat' => $this->lat,
            'lon' => $this->lon,
            'image' => $this->image,
            'serviceCenter_image'=>url('/').'/images/service_center/'.$this->image,
        ];
    }
}
