<?php

namespace App\Http\Resources\Products;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductsResource extends JsonResource
{

    public function toArray($request)
    {

        return[
            'name'=>$this->name,
            'name_bn'=>$this->name_bn,
            'code'=>$this->code,
            'product_image'=>$this->product_image,
            'details'=>$this->details,
            'type'=>$this->type
        ];
    }
}
