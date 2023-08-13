<?php

namespace App\Http\Resources\Engineer;

use Illuminate\Http\Resources\Json\ResourceCollection;

class EngineerCollection extends ResourceCollection
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
            'data'=>$this->collection->transform(function ($engineer){
                return[
                    'id' =>$engineer->id,
                    'date' =>$engineer->date,
                    'user_id' =>$engineer->user_id,
                    'report_actual' =>$engineer->report_actual,
                    'app_dashboard_actual' =>$engineer->app_dashboard_actual,
                    'team_coordination_actual' =>$engineer->team_coordination_actual,
                ];
            })
        ];
    }
}
