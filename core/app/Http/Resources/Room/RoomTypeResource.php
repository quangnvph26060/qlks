<?php

namespace App\Http\Resources\Room;

use Illuminate\Http\Resources\Json\JsonResource;

class RoomTypeResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'main_image' => $this->main_image,
            'total_adult' => $this->total_adult,
            'total_child' => $this->total_child,
            'description' => $this->description,
            'cancellation_fee' => $this->cancellation_fee,
            'cancellation_policy' => $this->cancellation_policy,
        ];
    }
}

