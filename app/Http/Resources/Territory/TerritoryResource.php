<?php

namespace App\Http\Resources\Territory;

use Illuminate\Http\Resources\Json\JsonResource;

class TerritoryResource extends JsonResource
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
            'id' =>$this->id,
            'area_id' =>$this->area_id,
            'name' =>$this->name,
            'code' =>$this->code,
        ];
    }
}
