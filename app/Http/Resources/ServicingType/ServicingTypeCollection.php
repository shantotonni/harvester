<?php

namespace App\Http\Resources\ServicingType;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ServicingTypeCollection extends ResourceCollection
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
            'data' => $this->collection->transform(function ($servicing_type){
                return[
                    'id'=>$servicing_type->id,
                    'servicing_type'=>$servicing_type->name,
                    'code'=>$servicing_type->code,

                ];
            })
        ];
    }
}
