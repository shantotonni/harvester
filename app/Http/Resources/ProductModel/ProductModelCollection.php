<?php

namespace App\Http\Resources\ProductModel;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductModelCollection extends ResourceCollection
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
            'data' => $this->collection->transform(function ($model){
                return[
                    'id'=>$model->id,
                    'product_id'=>$model->product_id,
                    'model_name'=>$model->model_name,
                    'model_code'=>$model->model_code,

                ];
            })
        ];
    }
}
