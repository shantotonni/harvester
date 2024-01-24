<?php

namespace App\Http\Resources\HarvesterService;

use App\Models\SparePartsMirror;
use Illuminate\Http\Resources\Json\ResourceCollection;

class HarvesterServiceCollection extends ResourceCollection
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
            'data'=>$this->collection->transform(function ($harvester_service){
                $mirror_products = SparePartsMirror::select('ProductCode','ProductName','UnitPrice')
                    ->where('Business','W')
                    ->where('ProductCode',$harvester_service->parts_code)
                    ->where('Active','Y')
                    ->first();
                return [
                    'id' => $harvester_service->id,
                    'model_id' => $harvester_service->model_id,
                    'model_name'=>isset($harvester_service->ProductModel) ? $harvester_service->ProductModel->model_name:'',
                    'model_name_bn'=>isset($harvester_service->ProductModel) ? $harvester_service->ProductModel->model_name_bn:'',
                    'servicing_type_id' => $harvester_service->servicing_type_id,
                    'servicing_name'=>isset($harvester_service->ServicingType) ? $harvester_service->ServicingType->name:'',
                    'from_hr' => $harvester_service->from_hr,
                    'to_hr' => $harvester_service->to_hr,
                    'fix_hour' => $harvester_service->fix_hour,
                    'parts_name' => $harvester_service->parts_name,
                    'parts_code' => $harvester_service->parts_code,
                    'ProductName'=>isset($harvester_service->SparePartsMirror) ? $harvester_service->SparePartsMirror->ProductName:'',
                    'ProductCode' => $mirror_products,
                    'quantity' => $harvester_service->quantity,
                    'servicing_status' => $harvester_service->servicing_status,
                ];
            })
        ];
    }
}
