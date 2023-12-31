<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CustomerCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection->transform(function ($customer) {
                return [
                    'id' => $customer->id,
                    'product_id' => $customer->product_id,
                    'product_name' => isset($customer->Products) ? $customer->Products->name : '',
                    'product_name_bn' => isset($customer->Products) ? $customer->Products->name_bn : '',
                    'model' => isset($customer->chassis_one) ? $customer->chassis_one->model: '',
                    'name' => $customer->name,
                    'email' => $customer->email,
                    'mobile' => $customer->mobile,
                    'RDate' => date('Y-m-d',strtotime($customer->created_at)),
                    'password' => $customer->password,
                    'service_hour' => $customer->service_hour,
                    'date_of_purchase' => $customer->date_of_purchase,
                    'area_id' => $customer->area_id,
                    'area_name'=>isset($customer->area) ? $customer->area->name:'',
                    'area_name_bn'=>isset($customer->area) ? $customer->area->name_bn:'',
                    'district_id' => $customer->district_id,
                    'district_name' => isset($customer->District) ? $customer->District->name : '',
                    'district_name_bn' => isset($customer->District) ? $customer->District->name_bn : '',
                    'address' => $customer->address,
                    'chassis' =>  isset($customer->chassis_one) ? $customer->chassis_one->chassis_no : '',
                     'chassis_image' => $customer->chassis_image,
                    'customer_type' => $customer->customer_type,
                    'image' => $customer->image,
                    'customer_image'=>url('/').'/images/customer/'.$customer->image,
                    //'customerData' => isset($customer->chassis_one->invoice_details->invoice) ? $customer->chassis_one->invoice_details->invoice : '',
                ];
            })
        ];
    }
}
