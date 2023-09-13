<?php

namespace App\Http\Resources\SmartAssist;

use Illuminate\Http\Resources\Json\JsonResource;

class SmartAssistResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return[
            'chassis_no'=>$this->chassis_no,
            'sarid'=>$this->SARID,
            'password'=>$this->password,
        ] ;
    }
}
