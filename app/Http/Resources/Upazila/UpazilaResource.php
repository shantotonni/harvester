<?php

namespace App\Http\Resources\Upazila;

use Illuminate\Http\Resources\Json\JsonResource;

class UpazilaResource extends JsonResource
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
            'upazila_id' => $this->id,
            'district_id' => $this->district_id,
            'district_name' => isset($this->district) ? $this->district->district_name : '',
            'upazila_name_bn' => $this->name_bn,
            'upazila_name' => $this->name,
            'code' => $this->code
        ];
    }
}
