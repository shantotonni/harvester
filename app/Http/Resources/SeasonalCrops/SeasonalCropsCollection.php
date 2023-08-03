<?php

namespace App\Http\Resources\SeasonalCrops;

use Illuminate\Http\Resources\Json\ResourceCollection;

class SeasonalCropsCollection extends ResourceCollection
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
            'data' => $this->collection->transform(function ($seasonal_crop) {
                return [
                    'id' => $seasonal_crop->id,
                    'district_id' => $seasonal_crop->district_id,
                    'district_name' => isset($seasonal_crop->District) ? $seasonal_crop->District->name : '',
                    'seasonal_crops_id' => $seasonal_crop->seasonal_crops_id,
                    'seasonal_crops_name' => isset($seasonal_crop->Crop) ? $seasonal_crop->Crop->name : '',
                    'date_from' => date('d F Y',strtotime($seasonal_crop->date_from)),
                    'date_fromd' => $seasonal_crop->date_from,
                    'date_to' => date('d F Y',strtotime($seasonal_crop->date_to)),
                    'date_tod' => $seasonal_crop->date_to
                ];
            })

        ];

    }
}
