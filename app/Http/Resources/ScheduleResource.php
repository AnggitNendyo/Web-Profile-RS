<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ScheduleResource extends JsonResource
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
            'day_of_week' => $this->day_of_week,
            'start_time' => \Carbon\Carbon::parse($this->start_time)->format('H:i'),
            'end_time' => \Carbon\Carbon::parse($this->end_time)->format('H:i'),
            'location_clinic' => $this->location_clinic,
        ];
    }
}
