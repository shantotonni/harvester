<?php

namespace App\Http\Resources\ServicingType;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ServicingTypeCollection extends ResourceCollection
{

    public function toArray($request)
    {
        return [
            'data' => $this->collection->transform(function ($servicing_type){
                return[
                    'id'=>$servicing_type->id,
                    'name'=>$servicing_type->name,
                    'code'=>$servicing_type->code,

                ];
            })
        ];
    }
}
