<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PropertyUnit;
use App\Http\Resources\PropertyUnitResource;
use Illuminate\Http\Request;

/**
 * @group Property Unit Management
 * 
 * APIs for managing property units (apartments, villas, rooms within properties)
 * 
 * @authenticated
 */
class PropertyUnitController extends Controller
{
    /**
     * List property units
     * 
     * Retrieve a paginated list of property units with filtering options.
     * 
     * @queryParam property_id string Filter units by property UUID. Example: 9d5e8c7a-1234-5678-90ab-cdef12345678
     * @queryParam status string Filter by unit status (available, occupied, maintenance). Example: available
     * @queryParam type string Filter by unit type (apartment, villa, room, office, shop). Example: apartment
     * @queryParam min_rent numeric Minimum monthly rent. Example: 1000
     * @queryParam max_rent numeric Maximum monthly rent. Example: 5000
     * @queryParam bedrooms integer Number of bedrooms. Example: 2
     * @queryParam bathrooms integer Number of bathrooms. Example: 2
     * @queryParam page integer Page number. Example: 1
     * @queryParam per_page integer Items per page (max 100). Example: 20
     * 
     * @response 200 scenario="Success" {
     *   "data": [
     *     {
     *       "id": "9d5e8c7a-1234-5678-90ab-cdef12345678",
     *       "property_id": "9d5e8c7a-1111-2222-3333-444444444444",
     *       "unit_number": "A-101",
     *       "type": "apartment",
     *       "status": "available",
     *       "monthly_rent": 2500.00,
     *       "bedrooms": 2,
     *       "bathrooms": 2,
     *       "size_sqft": 1200.00,
     *       "floor": 1,
     *       "created_at": "2025-11-08T10:00:00.000000Z"
     *     }
     *   ],
     *   "meta": {
     *     "current_page": 1,
     *     "total": 50
     *   }
     * }
     * 
     * @response 401 scenario="Unauthenticated" {
     *   "message": "Unauthenticated."
     * }
     */
    public function index(Request $request)
    {
        $query = PropertyUnit::with('property');
        
        if ($request->filled('property_id')) {
            $query->where('property_id', $request->property_id);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        if ($request->filled('min_rent')) {
            $query->where('monthly_rent', '>=', $request->min_rent);
        }
        
        if ($request->filled('max_rent')) {
            $query->where('monthly_rent', '<=', $request->max_rent);
        }
        
        if ($request->filled('bedrooms')) {
            $query->where('bedrooms', $request->bedrooms);
        }
        
        if ($request->filled('bathrooms')) {
            $query->where('bathrooms', $request->bathrooms);
        }
        
        $perPage = min($request->input('per_page', 20), 100);
        $units = $query->paginate($perPage);
        
        return PropertyUnitResource::collection($units);
    }

    /**
     * Create a new property unit
     * 
     * Create a new unit within a property. Only landlords can create units for their properties.
     * 
     * @bodyParam property_id string required Property UUID. Example: 9d5e8c7a-1111-2222-3333-444444444444
     * @bodyParam unit_number string required Unit identifier/number. Example: A-101
     * @bodyParam type string required Unit type (apartment, villa, room, office, shop). Example: apartment
     * @bodyParam status string Unit status (available, occupied, maintenance). Defaults to 'available'. Example: available
     * @bodyParam monthly_rent numeric required Monthly rent amount. Example: 2500.00
     * @bodyParam security_deposit numeric Security deposit amount. Example: 5000.00
     * @bodyParam bedrooms integer Number of bedrooms. Example: 2
     * @bodyParam bathrooms integer Number of bathrooms. Example: 2
     * @bodyParam size_sqft numeric Size in square feet. Example: 1200.00
     * @bodyParam floor integer Floor number. Example: 1
     * @bodyParam description string Unit description. Example: Spacious 2BR apartment with balcony
     * @bodyParam amenities array List of amenities. Example: ["AC", "Parking", "Balcony"]
     * @bodyParam lease_terms object Lease terms and conditions. Example: {"minimum_lease_months": 12, "notice_period_days": 60}
     * 
     * @response 201 scenario="Created" {
     *   "data": {
     *     "id": "9d5e8c7a-1234-5678-90ab-cdef12345678",
     *     "property_id": "9d5e8c7a-1111-2222-3333-444444444444",
     *     "unit_number": "A-101",
     *     "type": "apartment",
     *     "status": "available",
     *     "monthly_rent": 2500.00,
     *     "bedrooms": 2,
     *     "bathrooms": 2
     *   }
     * }
     * 
     * @response 422 scenario="Validation Error" {
     *   "message": "The given data was invalid.",
     *   "errors": {
     *     "unit_number": ["The unit number field is required."]
     *   }
     * }
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'unit_number' => 'required|string|max:50',
            'type' => 'required|in:apartment,villa,room,office,shop',
            'status' => 'nullable|in:available,occupied,maintenance',
            'monthly_rent' => 'required|numeric|min:0',
            'security_deposit' => 'nullable|numeric|min:0',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'size_sqft' => 'nullable|numeric|min:0',
            'floor' => 'nullable|integer',
            'description' => 'nullable|string',
            'amenities' => 'nullable|array',
            'lease_terms' => 'nullable|array',
        ]);
        
        $unit = PropertyUnit::create($validated);
        
        return new PropertyUnitResource($unit);
    }

    /**
     * Get property unit details
     * 
     * Retrieve detailed information about a specific property unit.
     * 
     * @urlParam id string required Unit UUID. Example: 9d5e8c7a-1234-5678-90ab-cdef12345678
     * 
     * @response 200 scenario="Success" {
     *   "data": {
     *     "id": "9d5e8c7a-1234-5678-90ab-cdef12345678",
     *     "property_id": "9d5e8c7a-1111-2222-3333-444444444444",
     *     "unit_number": "A-101",
     *     "type": "apartment",
     *     "status": "available",
     *     "monthly_rent": 2500.00,
     *     "security_deposit": 5000.00,
     *     "bedrooms": 2,
     *     "bathrooms": 2,
     *     "size_sqft": 1200.00,
     *     "floor": 1,
     *     "description": "Spacious 2BR apartment",
     *     "amenities": ["AC", "Parking"],
     *     "property": {
     *       "id": "9d5e8c7a-1111-2222-3333-444444444444",
     *       "name": "Downtown Residences"
     *     }
     *   }
     * }
     * 
     * @response 404 scenario="Not Found" {
     *   "message": "Property unit not found."
     * }
     */
    public function show(string $id)
    {
        $unit = PropertyUnit::with('property')->findOrFail($id);
        
        return new PropertyUnitResource($unit);
    }

    /**
     * Update property unit
     * 
     * Update an existing property unit. Only landlords can update their property units.
     * 
     * @urlParam id string required Unit UUID. Example: 9d5e8c7a-1234-5678-90ab-cdef12345678
     * 
     * @bodyParam unit_number string Unit identifier/number. Example: A-102
     * @bodyParam type string Unit type (apartment, villa, room, office, shop). Example: apartment
     * @bodyParam status string Unit status (available, occupied, maintenance). Example: occupied
     * @bodyParam monthly_rent numeric Monthly rent amount. Example: 2600.00
     * @bodyParam security_deposit numeric Security deposit amount. Example: 5200.00
     * @bodyParam bedrooms integer Number of bedrooms. Example: 2
     * @bodyParam bathrooms integer Number of bathrooms. Example: 2
     * @bodyParam size_sqft numeric Size in square feet. Example: 1250.00
     * @bodyParam floor integer Floor number. Example: 1
     * @bodyParam description string Unit description. Example: Updated description
     * @bodyParam amenities array List of amenities. Example: ["AC", "Parking", "Balcony", "Pool"]
     * @bodyParam lease_terms object Lease terms and conditions. Example: {"minimum_lease_months": 12}
     * 
     * @response 200 scenario="Success" {
     *   "data": {
     *     "id": "9d5e8c7a-1234-5678-90ab-cdef12345678",
     *     "unit_number": "A-102",
     *     "monthly_rent": 2600.00
     *   }
     * }
     * 
     * @response 404 scenario="Not Found" {
     *   "message": "Property unit not found."
     * }
     */
    public function update(Request $request, string $id)
    {
        $unit = PropertyUnit::findOrFail($id);
        
        $validated = $request->validate([
            'unit_number' => 'sometimes|string|max:50',
            'type' => 'sometimes|in:apartment,villa,room,office,shop',
            'status' => 'sometimes|in:available,occupied,maintenance',
            'monthly_rent' => 'sometimes|numeric|min:0',
            'security_deposit' => 'nullable|numeric|min:0',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'size_sqft' => 'nullable|numeric|min:0',
            'floor' => 'nullable|integer',
            'description' => 'nullable|string',
            'amenities' => 'nullable|array',
            'lease_terms' => 'nullable|array',
        ]);
        
        $unit->update($validated);
        
        return new PropertyUnitResource($unit);
    }

    /**
     * Delete property unit
     * 
     * Delete a property unit. Only landlords can delete their property units.
     * Cannot delete units with active lease contracts.
     * 
     * @urlParam id string required Unit UUID. Example: 9d5e8c7a-1234-5678-90ab-cdef12345678
     * 
     * @response 200 scenario="Success" {
     *   "message": "Property unit deleted successfully."
     * }
     * 
     * @response 404 scenario="Not Found" {
     *   "message": "Property unit not found."
     * }
     * 
     * @response 409 scenario="Has Active Contracts" {
     *   "message": "Cannot delete unit with active lease contracts."
     * }
     */
    public function destroy(string $id)
    {
        $unit = PropertyUnit::findOrFail($id);
        
        // Check for active lease contracts
        if ($unit->leaseContracts()->where('status', 'active')->exists()) {
            return response()->json([
                'message' => __('messages.unit_has_active_contracts')
            ], 409);
        }
        
        $unit->delete();
        
        return response()->json([
            'message' => __('messages.unit_deleted')
        ]);
    }
}
