<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PushNotificationCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return $this->collection->transform(function ($notification) {
                return [
                    'id' => $notification->id,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'image'=>url('/').'/images/notification/'.$notification->image,
                ];
            });
    }
}
