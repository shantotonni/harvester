<?php

namespace App\Http\Resources\ServiceTips;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ServiceTipsCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        return $this->collection->transform(function ($service_tip) {
            return [
                'id' => $service_tip->id,
                'title' => $service_tip->title,
                'type' => $service_tip->type,
                'active' => $service_tip->active,
                'description' => $service_tip->description,
                'video_link' => $service_tip->video_link,
                'image' => $service_tip->image,
                'serviceTips_image' => url('/') . '/images/ServiceTips/' . $service_tip->image,
            ];
        });
    }
}




