<?php

namespace App\Http\Resources\HarvesterParts;

use App\Models\ProductModel;
use App\Models\SparePartsMirror;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\DB;

class  HarvesterPartsCollection extends ResourceCollection
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

                $prudtModel = [];
                foreach ($harvester_part->HarvesterPartsModels as $item){
                    $model = ProductModel::query()->where('id',$item->model_id)->first();
                    $prudtModel [] = [
                        'id'=>$model->id,
                        'product_id'=>$model->product_id,
                        'name'=>isset($model->Products) ? $model->Products->name:'',
                        'model_name'=>$model->model_name,
                        'model_name_bn'=>$model->model_name_bn,
                        'model_code'=>$model->model_code,
                        'details'=>$model->model_details,
                        'image'=>$model->model_image,
                        'model_image'=>url('/').'/images/ProductModel/'.$model->image,
                    ];
                }

                return [
                    'parts_id' => $harvester_part->parts_id,
                    'ProductName'=>isset($harvester_part->SparePartsMirror) ? $harvester_part->SparePartsMirror->ProductName:'',
                    'parts' => $harvester_part->ProductCode,
                    'ProductCode' => $mirror_products,
                    //age chile
                    //'UnitPrice' => isset($harvester_part->SparePartsMirror) ? $harvester_part->SparePartsMirror->UnitPrice:'',

                    //new
                    'UnitPrice' => isset($harvester_part->SparePartsMirror) ? $harvester_part->SparePartsMirror->MRP:'',
//                    'product_model_id' => $harvester_part->product_model_id,
//                    'model_name'=>isset($harvester_part->ProductModel) ? $harvester_part->ProductModel->model_name:'',
                    'section_id' => $harvester_part->section_id,
                    'product_model' => $prudtModel,
                    'section_name'=>isset($harvester_part->section) ? $harvester_part->section->name:'',
                    'custom_name' => $harvester_part->custom_name,
                    'parts_type' => $harvester_part->parts_type,
                    'image' => $harvester_part->image,
                    'harvesterParts_image'=>url('/').'/images/HarvesterParts/'.$harvester_part->image,
                ];
            })
        ];

    }
}
