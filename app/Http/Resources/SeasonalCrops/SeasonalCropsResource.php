<?php

namespace App\Http\Resources\SeasonalCrops;

use Illuminate\Http\Resources\Json\JsonResource;

class SeasonalCropsResource extends JsonResource
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
            'id'=>$this->id,
            'district_id'=>$this->district_id,
            'district_name'=>isset($this->district) ? $this->district->name:'',
            'seasonal_crops_id'=> $this->seasonal_crops_id,
            'seasonal_crops_name'=>isset($this->crop) ? $this->crop->name:'',
            'date_from'=>$this->date_from,
            'date_to'=>$this->date_to
        ];
    }
}
