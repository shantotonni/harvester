<?php

namespace App\Http\Resources\Products;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductsCollection extends ResourceCollection
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
            'data'=>$this->collection->transform(function ($product){
                return[
                  'name'=>$product->name,
                   'name_bn'=>$product->name_bn,
                    'code'=>$product->code,
                    'product_image'=>$product->product_image,
                    'details'=>$product->details,
                    'type'=>$product->type
                ];
            })
        ];
    }
}
