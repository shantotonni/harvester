<?php

namespace App\Http\Resources\HarvesterInfo;

use Illuminate\Http\Resources\Json\JsonResource;

class HarvesterInfoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'product_category_name' => $this->product_name,
            'product_id' => $this->product_id,
            'product_name'=>isset($this->product) ? $this->product->name:'',
            'model_id' => $this->model_id,
            'model_name'=>isset($this->model) ? $this->model->name:'',
            'horse_power' => $this->horse_power,
            'details' => $this->details,
            'image' => $this->image
        ];
    }
}
