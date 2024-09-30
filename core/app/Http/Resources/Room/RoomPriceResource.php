<?php

namespace App\Http\Resources\Room;

use Illuminate\Http\Resources\Json\JsonResource;

class RoomPriceResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'price' => $this->price,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'status' => $this->status,
            'pivot' => [
                'start_date' => $this->pivot->start_date,
                'end_date' => $this->pivot->end_date,
                'status' => $this->pivot->status,
            ],
        ];
    }
}
