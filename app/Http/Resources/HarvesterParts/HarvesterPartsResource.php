<?php

namespace App\Http\Resources\HarvesterParts;

use Illuminate\Http\Resources\Json\JsonResource;

class HarvesterPartsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'code'=>isset($this->Products) ? $this->Products->code:'',
            'product_model_id' => $this->product_model_id,
            'model_name'=>isset($this->model) ? $this->model->name:'',
            'section_id' => $this->section_id,
            'section_name'=>isset($this->section) ? $this->section->name:'',
            'custom_name' => $this->custom_name,
            'parts_type' => $this->parts_type,
            'image' => $this->image,
            'harvesterParts_image'=>url('/').'/images/HarvesterParts/'.$this->image,
        ];
    }
}
