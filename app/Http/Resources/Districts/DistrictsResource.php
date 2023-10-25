<?php

namespace App\Http\Resources\Districts;

use Illuminate\Http\Resources\Json\JsonResource;

class DistrictsResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'district_id'  => $this->id,
            'district_name'=>$this->name,
            'district_name_bn'=>$this->name_bn
        ];
    }
}
