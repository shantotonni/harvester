<?php

namespace App\Http\Resources\MirrorProduct;

use Illuminate\Http\Resources\Json\ResourceCollection;

class MirrorProductCollection extends ResourceCollection
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
            'data'=>$this->collection->transform(function ($mirror_product){
                return[
                    'ProductName'=>$mirror_product->ProductName,
                    'ProductCode'=>$mirror_product->ProductCode,
                    'UnitPrice'=>$mirror_product->UnitPrice,
                    'Business'=>$mirror_product->Business,
                    'Active'=>$mirror_product->Active
                ];
            })
        ];
    }
}
