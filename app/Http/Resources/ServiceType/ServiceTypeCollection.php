<?php

namespace App\Http\Resources\ServiceType;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ServiceTypeCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection->transform(function ($servicetype){
                return[
                    'id'=>$servicetype->id,
                    'servicetypename'=>$servicetype->name,
                    'servicetypenamebn'=>$servicetype->name_bn,
                    'code'=>$servicetype->code,

                ];
            })
        ];
    }
}
