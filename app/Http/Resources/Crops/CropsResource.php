<?php

namespace App\Http\Resources\Crops;

use Illuminate\Http\Resources\Json\JsonResource;

class CropsResource extends JsonResource
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
            'seasonal_crops_id'=>$this->id,
            'seasonal_crops_name'=>$this->name
        ];
    }
}
