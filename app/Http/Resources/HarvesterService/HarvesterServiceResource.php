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
            'modelname'=>isset($this->model) ? $this->model->name:'',
            'servicing_type_id' => $this->servicing_type_id,
            'servicingname' => isset($this->servicing) ? $this->servicing->name : '',
            'from_hr' => $this->from_hr,
            'to_hr' => $this->to_hr,
        ];
    }
}
