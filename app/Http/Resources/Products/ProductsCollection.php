<?php

namespace App\Http\Resources\Products;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductsCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data'=>$this->collection->transform(function ($product){
                return[
                   'id'=>$product->id,
                   'name'=>$product->name,
                   'name_bn'=>$product->name_bn,
                    'code'=>$product->code,
                    'details'=>$product->details,
                    'type'=>$product->type,
                    'product_image'=>$product->product_image,
                    'image'=>url('/').'/images/ProductModel/'.$product->image,
                ];
            })
        ];
    }
}
