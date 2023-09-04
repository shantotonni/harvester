<?php

namespace App\Http\Resources\HarvesterService;

use Illuminate\Http\Resources\Json\JsonResource;

class HarvesterServiceResource extends JsonResource
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
            'id' => $this->id,
            'model_id' => $this->model_id,
            'model_name'=>isset($this->model) ? $this->model->name:'',
            'servicing_type_id' => $this->servicing_type_id,
            'servicing_name' => isset($this->servicing) ? $this->servicing->name : '',
            'from_hr' => $this->from_hr,
            'to_hr' => $this->to_hr,
            'fixe_hour' => $this->fixe_hour,
            'parts_name' => $this->parts_name,
            'parts_code' => $this->parts_code,
            'price' => $this->price,
            'ProductCode'=>isset($this->MirrorProducts) ? $this->MirrorProducts->ProductCode:'',
            'UnitPrice'=>isset($this->MirrorProducts) ? $this->MirrorProducts->UnitPrice:'',
            'quantity' => $this->quantity,
            'servicing_status' => $this->servicing_status,
        ];
    }
}
