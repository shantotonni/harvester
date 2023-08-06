<?php

namespace App\Http\Resources\ServiceTips;

use Illuminate\Http\Resources\Json\JsonResource;

class ServiceTipsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'type' => $this->type,
            'active' => $this->active,
            'description' => $this->description,
            'video_link' => $this->video_link,
            'image' => $this->image,
            'serviceTips_image' => url('/') . '/images/ServiceTips/' . $this->image,
        ];
    }
}
