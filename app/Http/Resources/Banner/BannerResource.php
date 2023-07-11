<?php

namespace App\Http\Resources\Banner;

use Illuminate\Http\Resources\Json\JsonResource;

class BannerResource extends JsonResource
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
            'data'=>$this->collection->transform(function ($banner){
                return [
                    'ID'=>$banner->ID,
                    'BannerName'=>$banner->BannerName,
                    'BannerImage'=>$banner->BannerImage,
                ];
            })
        ];
    }
}
