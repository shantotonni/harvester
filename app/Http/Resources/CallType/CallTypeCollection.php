<?php

namespace App\Http\Resources\CallType;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CallTypeCollection extends ResourceCollection
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
            'data'=>$this->collection->transform(function ($call_by){
                return[
                    'id' =>$call_by->id,
                    'name' =>$call_by->name,
                    'name_bn' =>$call_by->name_bn,
                    'code' =>$call_by->code,
                ];
            })
        ];
    }
}
