<?php

namespace App\Http\Resources\Showroom;

use Illuminate\Http\Resources\Json\JsonResource;

class ShowroomResource extends JsonResource
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
            'id' => $this->showroom_id,
            'district_id' => $this->district_id,
            'district_name' => isset($this->District) ? $this->District->name : '',
            'owner_name' => $this->owner_name,
            'showroom_name' => $this->showroom_name,
            'address' => $this->address,
            'mobile' => $this->mobile,
            'lat' => $this->lat,
            'long' => $this->long,
            'image' => $this->image
        ];
    }
}
