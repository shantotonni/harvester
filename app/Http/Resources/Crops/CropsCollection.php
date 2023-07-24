<?php

namespace App\Http\Resources\Crops;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CropsCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data'=>$this->collection->transform(function ($crop){
                return[
                  'seasonal_crops_id'=>$crop->id,
                'seasonal_crops_name'=>$crop->name

                ];
            })
        ];
    }
}
