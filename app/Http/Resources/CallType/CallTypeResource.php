<?php

namespace App\Http\Resources\CallType;

use Illuminate\Http\Resources\Json\JsonResource;

class CallTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' =>$this->id,
            'name' =>$this->name,
            'name_bn' =>$this->name_bn,
            'code' =>$this->code,
        ];
    }
}
