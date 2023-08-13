<?php

namespace App\Http\Resources\Territory;

use Illuminate\Http\Resources\Json\ResourceCollection;
use phpDocumentor\Reflection\Types\Array_;

class TerritoryCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return[
          'data'=>$this->collection->transform(function ($territory){
              return[
                  'id' =>$territory->id,
                  'name' =>$territory->name,
                  'code' =>$territory->code,
                  'area_id' =>$territory->area_id,
              ];
          })
        ];
    }
}
