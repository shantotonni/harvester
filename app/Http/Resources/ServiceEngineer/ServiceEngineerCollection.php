<?php

namespace App\Http\Resources\ServiceEngineer;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ServiceEngineerCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return $this->collection->transform(function ($service_engineer){
           return[
              'id'=>$service_engineer->id,
              'district_id'=>$service_engineer->district_id,
              'district_name'=>isset($service_engineer->District)? $service_engineer->District->name:'',
              'engineer_name'=>$service_engineer->engineer_name,
              'mobile'=>$service_engineer->mobile,
              'address'=>$service_engineer->address,
           ] ;
        });

    }
}
