<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\PropertyResource;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PropertyController extends BaseApiController
{
    /**
     * Display a listing of properties.
     */
    public function index(Request $request)
    {
        $query = Property::with(['owner', 'units', 'amenities']);

        // Filter by owner if landlord role
        /** @var \App\Models\User $user */
        $user = $request->user();
        if ($user->hasRole('landlord')) {
            $query->where('owner_id', $user->id);
        }

        // Search filters
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('name_ar', 'like', "%{$search}%")
                  ->orWhere('property_code', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%");
            });
        }

        if ($request->has('property_type')) {
            $query->where('property_type', $request->property_type);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('city')) {
            $query->where('city', $request->city);
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        return $this->paginateResponse($query, PropertyResource::class, __('messages.properties_retrieved'));
    }

    /**
     * Store a newly created property.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_ar' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'property_type' => 'required|in:residential,commercial,mixed_use',
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'required|string|max:100',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'year_built' => 'nullable|integer|min:1800|max:' . date('Y'),
            'total_units' => 'nullable|integer|min:1',
            'total_floors' => 'nullable|integer|min:1',
            'parking_spaces' => 'nullable|integer|min:0',
            'total_area' => 'nullable|numeric|min:0',
            'land_area' => 'nullable|numeric|min:0',
            'purchase_price' => 'nullable|numeric|min:0',
            'purchase_date' => 'nullable|date',
            'current_value' => 'nullable|numeric|min:0',
        ]);

        $validated['owner_id'] = $request->user()->id;
        $validated['status'] = 'active';

        $property = Property::create($validated);

        return $this->successResponse(
            new PropertyResource($property->load(['owner', 'units', 'amenities'])),
            __('messages.property_created'),
            201
        );
    }

    /**
     * Display the specified property.
     */
    public function show(Property $property)
    {
        // Check authorization
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user->hasRole('landlord') && $property->owner_id !== Auth::id()) {
            return $this->errorResponse(__('messages.unauthorized'), 403);
        }

        $property->load(['owner', 'manager', 'units', 'amenities', 'leaseContracts', 'maintenanceRequests']);

        return $this->successResponse(
            new PropertyResource($property),
            __('messages.properties_retrieved')
        );
    }

    /**
     * Update the specified property.
     */
    public function update(Request $request, Property $property)
    {
        // Check authorization
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user->hasRole('landlord') && $property->owner_id !== Auth::id()) {
            return $this->errorResponse(__('messages.unauthorized'), 403);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'name_ar' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'property_type' => 'sometimes|in:residential,commercial,mixed_use',
            'address_line_1' => 'sometimes|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'sometimes|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'sometimes|string|max:100',
            'status' => 'sometimes|in:active,inactive,under_maintenance',
        ]);

        $property->update($validated);

        return $this->successResponse(
            new PropertyResource($property->load(['owner', 'units', 'amenities'])),
            __('messages.property_updated')
        );
    }

    /**
     * Remove the specified property.
     */
    public function destroy(Property $property)
    {
        // Check authorization
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user->hasRole('landlord') && $property->owner_id !== Auth::id()) {
            return $this->errorResponse(__('messages.unauthorized'), 403);
        }

        $property->delete();

        return $this->successResponse(null, __('messages.property_deleted'));
    }
}
