<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class HarvesterServiceDetailsCollection extends ResourceCollection
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
            'data'=>$this->collection->transform(function ($details){
                return [
                    'id' => $details->id,
                    'model_id' => $details->model_id,
                    'model_name'=>isset($details->ProductModel) ? $details->ProductModel->model_name:'',
                    'servicing_type_id' => $details->servicing_type_id,
                    'servicing_name'=>isset($details->ServicingType) ? $details->ServicingType->name:'',
                    'from_hr' => $details->from_hr,
                    'to_hr' => $details->to_hr,
                ];
            })
        ];
    }
}
