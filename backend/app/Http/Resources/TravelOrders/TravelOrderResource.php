<?php

namespace App\Http\Resources\TravelOrders;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TravelOrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'requester_name' => $this->requester_name,
            'destination' => $this->destination,
            'departure_date' => $this->departure_date->format('Y-m-d'),
            'return_date' => $this->return_date->format('Y-m-d'),
            'status' => $this->status,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
            'created_by' => [
                'id' => $this->creator?->id,
                'name' => $this->creator?->name,
            ],
        ];
    }
}
