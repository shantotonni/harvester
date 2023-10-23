<?php

namespace App\Http\Resources\Section;

use Illuminate\Http\Resources\Json\ResourceCollection;

class SectionCollection extends ResourceCollection
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
            'data'=>$this->collection->transform(function ($section){
                return[
                  'section_id'=>$section->id,
                    'section_name'=>$section->name,
                    'code'=>$section->code,
                    'type'=>$section->type
                ];
            })
        ];
    }
}
