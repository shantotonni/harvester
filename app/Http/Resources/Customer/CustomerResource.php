<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
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
            'product_id' => $this->product_id,
            'product_name' => isset($this->product) ? $this->product->name : '',
            'model_id' => $this->model_id,
            'model_name' => isset($this->model) ? $this->model->model_name : '',
            'name' => $this->name,
            'email' => $this->email,
            'mobile' => $this->mobile,
            'password' => $this->password,
            'service_hour' => $this->service_hour,
            'date_of_purchase' => $this->date_of_purchase,
            'area_id' => $this->area_id,
            'area_name' => isset($this->area) ? $this->area->name : '',
            'district_id' => $this->district_id,
            'district_name' => isset($this->District) ? $this->District->name : '',
            'address' => $this->address,
            'chassis' => $this->chassis,
            'chassis_image' => $this->chassis_image,
            'customer_type' => $this->customer_type,
            'image' => $this->image,
            'customer_image'=>url('/').'/images/customer/'.$this->image,
        ];
    }
}
