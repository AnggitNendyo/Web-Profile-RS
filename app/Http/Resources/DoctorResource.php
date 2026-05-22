<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DoctorResource extends JsonResource
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
            'name' => $this->name,
            'full_name' => $this->full_name,
            'front_title' => $this->front_title,
            'back_title' => $this->back_title,
            'title' => $this->title,
            'specialty' => $this->relationLoaded('specialties') && $this->specialties->count() > 0 ? [
                'name' => $this->specialties->pluck('name')->join(', '),
                'slug' => $this->specialties->first()->slug
            ] : null,
            'specialties' => SpecialtyResource::collection($this->whenLoaded('specialties')),
            'education' => $this->education,
            'experience_years' => $this->experience_years,
            'photo_url' => $this->photo_path ? asset('storage/' . $this->photo_path) : null,
            'is_active' => $this->is_active,
            'schedules' => ScheduleResource::collection($this->whenLoaded('schedules')),
        ];
    }
}
