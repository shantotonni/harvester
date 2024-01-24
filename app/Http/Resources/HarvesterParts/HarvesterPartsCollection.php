<?php

namespace App\Http\Resources\HarvesterParts;

use App\Models\SparePartsMirror;
use Illuminate\Http\Resources\Json\ResourceCollection;

class HarvesterPartsCollection extends ResourceCollection
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
            'data' => $this->collection->transform(function ($harvester_part) {
                $mirror_products = SparePartsMirror::select('ProductCode','ProductName','UnitPrice')
                    ->where('Business','W')
                    ->where('ProductCode',$harvester_part->ProductCode)
                    ->where('Active','Y')
                    ->first();
                return [
                    'parts_id' => $harvester_part->parts_id,
                    'ProductName'=>isset($harvester_part->SparePartsMirror) ? $harvester_part->SparePartsMirror->ProductName:'',
                    'parts' => $harvester_part->ProductCode,
                    'ProductCode' => $mirror_products,
                    'UnitPrice'=>isset($harvester_part->SparePartsMirror) ? $harvester_part->SparePartsMirror->UnitPrice:'',
                    'product_model_id' => $harvester_part->product_model_id,
                    'model_name'=>isset($harvester_part->ProductModel) ? $harvester_part->ProductModel->model_name:'',
                    'section_id' => $harvester_part->section_id,
                    'section_name'=>isset($harvester_part->section) ? $harvester_part->section->name:'',
                    'custom_name' => $harvester_part->custom_name,
                    'image' => $harvester_part->image,
                    'harvesterParts_image'=>url('/').'/images/HarvesterParts/'.$harvester_part->image,
                ];
            })
        ];

    }
}
