<?php

namespace App\Http\Resources\HarvesterService;

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
                return [
                    'id' => $harvester_service->id,
                    'model_id' => $harvester_service->model_id,
//                    'model_name'=>isset($harvester_service->model) ? $harvester_service->model->name:'',
                    'servicing_type_id' => $harvester_service->model_id,
//                    'servicing_type' => isset($harvester_service->servicing) ? $harvester_service->servicing->name : '',
                    'from_hr' => $harvester_service->from_hr,
                    'to_hr' => $harvester_service->to_hr,
                ];
            })
        ];
    }
}
