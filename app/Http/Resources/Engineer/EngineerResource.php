<?php

namespace App\Http\Resources\Engineer;

use Illuminate\Http\Resources\Json\JsonResource;

class EngineerResource extends JsonResource
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
            'date' =>$this->date,
            'user_id' =>$this->user_id,
            'report_actual' =>$this->report_actual,
            'app_dashboard_actual' =>$this->app_dashboard_actual,
            'team_coordination_actual' =>$this->team_coordination_actual,
        ];
    }
}
