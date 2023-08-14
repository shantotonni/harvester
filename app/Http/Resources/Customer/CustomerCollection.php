<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CustomerCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection->transform(function ($customer) {
                return [
                    'id' => $customer->id,
                    'product_id' => $customer->product_id,
                    'product_name' => isset($customer->Products) ? $customer->Products->name : '',
                    'model_id' => $customer->model_id,
                    'model_name' => isset($customer->ProductModel) ? $customer->ProductModel->model_name : '',
                    'name' => $customer->name,
                    'email' => $customer->email,
                    'mobile' => $customer->mobile,
                    'password' => $customer->password,
                    'service_hour' => $customer->service_hour,
                    'date_of_purchase' => $customer->date_of_purchase,
                    'area_id' => $customer->area_id,
                    'area_name'=>isset($customer->area) ? $customer->area->name:'',
                    'district_id' => $customer->district_id,
                    'district_name' => isset($customer->District) ? $customer->District->name : '',
                    'address' => $customer->address,
                    'chassis' => $customer->chassis,
                    'chassis_image' => $customer->chassis_image,
                    'customer_type' => $customer->customer_type,
                    'image' => $customer->image,
                    'customer_image'=>url('/').'/images/customer/'.$customer->image,
                ];
            })
        ];
    }
}
