<?php

namespace App\Http\Resources\Upazila;

use Illuminate\Http\Resources\Json\ResourceCollection;

class UpazilaCollection extends ResourceCollection
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
            'data' => $this->collection->transform(function ($upazila) {
                return [
                    'upazila_id' => $upazila->id,
                    'district_id' => $upazila->district_id,
                    'district_name' => isset($upazila->district) ? $upazila->district->name : '',
                    'upazila_name_bn' => $upazila->name_bn,
                    'upazila_name' => $upazila->name,
                    'code' => $upazila->code
                ];
            })
        ];
    }
}
