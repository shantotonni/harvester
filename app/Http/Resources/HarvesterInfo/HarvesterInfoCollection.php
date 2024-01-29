<?php

namespace App\Http\Resources\HarvesterInfo;

use Illuminate\Http\Resources\Json\ResourceCollection;

class HarvesterInfoCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return $this->collection->transform(function ($harvester_info) {
                return [
                    'id' => $harvester_info->id,
                    'product_name' => $harvester_info->product_name,
                    'product_id' => $harvester_info->product_id,
                    'name'=>isset($harvester_info->Products) ? $harvester_info->Products->name:'',
                    'model_id' => $harvester_info->model_id,
                    'model_name'=>isset($harvester_info->ProductModel) ? $harvester_info->ProductModel->model_name:'',
                    'horse_power' => $harvester_info->horse_power,
                    'details' => $harvester_info->details,
                    'details2' => strip_tags($harvester_info->details),
                    'image' => $harvester_info->image,
                    'harvesterInfo_image'=>url('/').'/images/HarvesterInfo/'.$harvester_info->image,
                ];
            });
    }
}
