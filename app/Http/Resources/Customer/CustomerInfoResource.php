<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerInfoResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'mobile' => $this->mobile,
            'product_id' => $this->product_id,
            'product_name' => isset($this->Products) ? $this->Products->name : '',
            'product_name_bn' => isset($this->Products) ? $this->Products->name_bn : '',
            'model' => isset($this->chassis_one) ? $this->chassis_one->model: '',
            'district_id' => $this->district_id,
            'district_name' => isset($this->District) ? $this->District->name : '',
            'district_name_bn' => isset($this->District) ? $this->District->name_bn : '',
            'address' => $this->address,
            'chassis' =>  isset($this->chassis_one) ? $this->chassis_one->chassis_no : '',
            'image' => $this->image,
            'customer_image'=>url('/').'/images/customer/'.$this->image,
        ];
    }
}
