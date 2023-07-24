<?php

namespace App\Http\Resources\Districts;

use Illuminate\Http\Resources\Json\JsonResource;

class DistrictsResource extends JsonResource
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
            'district_id'  => $this->id,
            'district_name'=>$this->name
        ];
    }
}
