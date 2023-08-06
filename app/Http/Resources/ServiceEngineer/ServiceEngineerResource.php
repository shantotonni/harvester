<?php

namespace App\Http\Resources\ServiceEngineer;

use Illuminate\Http\Resources\Json\JsonResource;

class ServiceEngineerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return[
            'id'=>$this->id,
            'district_id'=>$this->district_id,
            'district_name'=>isset($$this->District)? $$this->District->name:'',
            'engineer_name'=>$this->engineer_name,
            'mobile'=>$this->mobile,
            'address'=>$this->address,
        ] ;
    }
}
