<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PropertyResource extends JsonResource
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
            'property_code' => $this->property_code,
            'name' => $this->name,
            'name_ar' => $this->name_ar,
            'description' => $this->description,
            'description_ar' => $this->description_ar,
            'property_type' => $this->property_type,
            'address' => [
                'address_line_1' => $this->address_line_1,
                'address_line_2' => $this->address_line_2,
                'city' => $this->city,
                'state' => $this->state,
                'postal_code' => $this->postal_code,
                'country' => $this->country,
            ],
            'location' => [
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
            ],
            'specs' => [
                'year_built' => $this->year_built,
                'total_units' => $this->total_units,
                'total_floors' => $this->total_floors,
                'parking_spaces' => $this->parking_spaces,
                'total_area' => $this->total_area,
                'land_area' => $this->land_area,
            ],
            'financial' => [
                'purchase_price' => $this->purchase_price,
                'purchase_date' => $this->purchase_date?->format('Y-m-d'),
                'current_value' => $this->current_value,
                'property_tax_annual' => $this->property_tax_annual,
                'insurance_annual' => $this->insurance_annual,
            ],
            'management' => [
                'managed_by' => $this->managed_by,
                'management_start_date' => $this->management_start_date?->format('Y-m-d'),
            ],
            'status' => $this->status,
            'owner' => $this->whenLoaded('owner', fn() => new UserResource($this->owner)),
            'manager' => $this->whenLoaded('manager', fn() => new UserResource($this->manager)),
            'units' => $this->whenLoaded('units', fn() => PropertyUnitResource::collection($this->units)),
            'amenities' => $this->whenLoaded('amenities', fn() => $this->amenities),
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
