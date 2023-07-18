<?php

namespace App\Http\Resources\Area;

use Illuminate\Http\Resources\Json\ResourceCollection;

class AreaCollection extends ResourceCollection
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
            'data' => $this->collection->transform(function ($area){
              return[
                  'id'=>$area->id,
                  'areaname'=>$area->name,
                  'name_bn'=>$area->name_bn,

              ];
            })
        ];
    }
}
