<?php

namespace App\Http\Resources\ServiceRequest;

use Illuminate\Http\Resources\Json\JsonResource;

class ServiceRequestResource extends JsonResource
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
            'technitian_id'=>$this->technitian_id,

            'district_id'=>$this->district_id,
            'district_name'=>isset($this->district)? $this->district->name:'',

            'upazila_id'=>$this->upazila_id,
            'upazila_name'=>isset($this->upazila)? $this->upazila->name:'',

            'customer_moblie'=>$this->customer_moblie,
            'remarks'=>$this->remarks,
            'chassis_number'=>$this->chassis_number,
            'job_status'=>$this->job_status,

            'section_id'=>$this->section_id,
            'section_name'=>isset($this->section)? $this->section->name:'',

            'product_id'=>$this->product_id,
            'name'=>isset($this->product)? $this->product->name:'',

            'model_id'=>$this->model_id,
            'model_name'=>isset($this->ProductModel)? $this->ProductModel->model_name:'',

        ];
    }
}
