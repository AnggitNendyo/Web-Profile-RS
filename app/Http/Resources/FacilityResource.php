<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FacilityResource extends JsonResource
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
            'category' => $this->facilityCategory ? $this->facilityCategory->name : 'Lainnya',
            'name' => $this->name,
            'description' => $this->description,
            'price_estimation' => $this->price_estimation,
            'bed_capacity' => $this->bed_capacity,
            'features' => $this->features,
            'photos' => $this->getMedia('facility_photos')->map(function ($media) {
                return [
                    'id' => $media->id,
                    'url' => $media->getUrl(),
                ];
            }),
        ];
    }
}
