<?php

namespace App\Http\Resources\MirrorProduct;

use Illuminate\Http\Resources\Json\JsonResource;

class MirrorProductResource extends JsonResource
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
            'ProductName'=>$this->ProductName,
            'ProductCode'=>$this->ProductCode,
            'UnitPrice'=>$this->UnitPrice,
            'Business'=>$this->Business,
            'Active'=>$this->Active
        ];
    }
}
