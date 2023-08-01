<?php

namespace App\Http\Resources\ServiceRequest;

use Illuminate\Http\Resources\Json\ResourceCollection;
use function Symfony\Component\String\s;

class ServiceRequestCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return[
            'data'=>$this->collection->transform(function ($service_request)
            {
                return[
                    'id'=>$service_request->id,

                    'product_id'=>$service_request->product_id,
                    'name'=>isset($service_request->product)? $service_request->product->name:'',

                    'district_id'=>$service_request->district_id,
                    'district_name'=>isset($service_request->district)? $service_request->district->name:'',

                    'upazila_id'=>$service_request->upazila_id,
                    'upazila_name'=>isset($service_request->upazila)? $service_request->upazila->name:'',

                    'customer_id'=>$service_request->customer_id,
                    'customer_name'=>$service_request->customer_name,
                    'customer_moblie'=>$service_request->customer_moblie,
                    'remarks'=>$service_request->remarks,
                    'chassis_number'=>$service_request->chassis_number,

                    'job_status'=>$service_request->job_status,
                    'job_creator'=>$service_request->job_creator,

                    'section_id'=>$service_request->section_id,
                    'section_name'=>isset($service_request->section)? $service_request->section->name:'',

                    'model_id'=>$service_request->model_id,
                    'model_name'=>isset($service_request->model)? $service_request->model->model_name:'',

                ];
            })
        ];
    }
}
