<?php

namespace App\Http\Resources\ProductModel;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductModelResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return[
            'id'=>$this->id,
            'product_id'=>$this->product_id,
            'name'=>isset($this->Products) ? $this->Products->name:'',
            'model_name'=>$this->model_name,
            'model_name_bn'=>$this->model_name_bn,
            'model_code'=>$this->model_code,
            'image'=>$this->model_image,
            'model_details'=>$this->model_details,

        ];
    }
}
