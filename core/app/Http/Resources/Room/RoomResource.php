<?php

namespace App\Http\Resources\Room;

use Illuminate\Http\Resources\Json\JsonResource;

class RoomResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'room_type' => RoomTypeResource::collection($this->room->roomType),
            'price' => RoomPriceResource::collection($this->room->roomPricesActive),
        ];
    }
}
