<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'status' => $this->status,
            'roles' => $this->whenLoaded('roles', fn() => $this->roles->pluck('name')),
            'permissions' => $this->whenLoaded('roles', fn() => $this->getAllPermissions()->pluck('name')),
            'profile' => $this->whenLoaded('profile', fn() => [
                'first_name' => $this->profile->first_name,
                'last_name' => $this->profile->last_name,
                'preferred_language' => $this->profile->preferred_language,
                'preferred_currency' => $this->profile->preferred_currency,
                'timezone' => $this->profile->timezone,
                'avatar_url' => $this->profile->avatar_url,
            ]),
            'email_verified_at' => $this->email_verified_at?->toISOString(),
            'last_login_at' => $this->last_login_at?->toISOString(),
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
