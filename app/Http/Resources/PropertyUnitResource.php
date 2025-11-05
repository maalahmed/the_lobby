<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PropertyUnitResource extends JsonResource
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
            'uuid' => $this->uuid,
            'property_id' => $this->property_id,
            'unit_number' => $this->unit_number,
            'unit_type' => $this->unit_type,
            'floor_number' => $this->floor_number,
            'specs' => [
                'bedrooms' => $this->bedrooms,
                'bathrooms' => $this->bathrooms,
                'area_sqft' => $this->area_sqft,
                'area_sqm' => $this->area_sqm,
            ],
            'rental' => [
                'rent_amount' => $this->rent_amount,
                'rent_currency' => $this->rent_currency,
                'rent_frequency' => $this->rent_frequency,
                'deposit_amount' => $this->deposit_amount,
            ],
            'furnished_status' => $this->furnished_status,
            'available_from' => $this->available_from?->format('Y-m-d'),
            'status' => $this->status,
            'description' => $this->description,
            'description_ar' => $this->description_ar,
            'features' => $this->features,
            'property' => $this->whenLoaded('property', fn() => new PropertyResource($this->property)),
            'current_lease' => $this->whenLoaded('currentLease', fn() => new LeaseContractResource($this->currentLease)),
            'images' => $this->getMedia('images')->map(fn($media) => [
                'id' => $media->id,
                'url' => $media->getUrl(),
                'thumb_url' => $media->getUrl('thumb'),
            ]),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
