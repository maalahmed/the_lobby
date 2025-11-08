<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ServiceProvider;
use App\Http\Resources\ServiceProviderResource;
use Illuminate\Http\Request;

/**
 * @group Service Provider Management
 * 
 * APIs for managing service providers and vendors
 * 
 * @authenticated
 */
class ServiceProviderController extends Controller
{
    /**
     * List service providers
     * 
     * Retrieve a paginated list of service providers with filtering.
     * 
     * @queryParam service_type string Filter by service type (plumbing, electrical, hvac, cleaning, security, landscaping, other). Example: plumbing
     * @queryParam status string Filter by status (active, inactive, suspended). Example: active
     * @queryParam search string Search by company name or contact person. Example: ABC
     * @queryParam page integer Page number. Example: 1
     * @queryParam per_page integer Items per page (max 100). Example: 20
     * 
     * @response 200 scenario="Success" {
     *   "data": [
     *     {
     *       "id": "9d5e8c7a-1234-5678-90ab-cdef12345678",
     *       "provider_code": "SP-001",
     *       "company_name": "ABC Plumbing Services",
     *       "service_type": "plumbing",
     *       "contact_person": "John Smith",
     *       "phone": "+971501234567",
     *       "email": "info@abcplumbing.com",
     *       "status": "active",
     *       "rating": 4.5
     *     }
     *   ]
     * }
     */
    public function index(Request $request)
    {
        $query = ServiceProvider::query();
        
        if ($request->filled('service_type')) {
            $query->where('service_type', $request->service_type);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('company_name', 'like', "%{$search}%")
                  ->orWhere('contact_person', 'like', "%{$search}%")
                  ->orWhere('provider_code', 'like', "%{$search}%");
            });
        }
        
        $perPage = min($request->input('per_page', 20), 100);
        $providers = $query->paginate($perPage);
        
        return ServiceProviderResource::collection($providers);
    }

    /**
     * Add a new service provider
     * 
     * Register a new service provider in the system.
     * Only admins and landlords can add service providers.
     * 
     * @bodyParam user_id string User UUID (must have 'service_provider' role). Example: 9d5e8c7a-5555-6666-7777-888888888888
     * @bodyParam company_name string required Company name. Example: ABC Plumbing Services
     * @bodyParam service_type string required Service type (plumbing, electrical, hvac, cleaning, security, landscaping, other). Example: plumbing
     * @bodyParam license_number string Trade license number. Example: LIC-123456
     * @bodyParam contact_person string required Contact person name. Example: John Smith
     * @bodyParam phone string required Phone number. Example: +971501234567
     * @bodyParam email string required Email address. Example: info@abcplumbing.com
     * @bodyParam address string Company address. Example: Business Bay, Dubai
     * @bodyParam service_areas array Service areas. Example: ["Downtown", "Business Bay", "Marina"]
     * @bodyParam hourly_rate numeric Hourly service rate. Example: 150.00
     * @bodyParam emergency_rate numeric Emergency service rate. Example: 300.00
     * @bodyParam rating numeric Provider rating (0-5). Example: 4.5
     * @bodyParam certifications array Certifications. Example: ["Licensed Plumber", "Gas Fitter"]
     * @bodyParam insurance_details object Insurance information. Example: {"provider": "ABC Insurance", "policy_number": "POL-123", "expiry": "2026-12-31"}
     * 
     * @response 201 scenario="Created" {
     *   "data": {
     *     "id": "9d5e8c7a-1234-5678-90ab-cdef12345678",
     *     "provider_code": "SP-001",
     *     "company_name": "ABC Plumbing Services",
     *     "status": "active"
     *   }
     * }
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'company_name' => 'required|string|max:255',
            'service_type' => 'required|in:plumbing,electrical,hvac,cleaning,security,landscaping,other',
            'license_number' => 'nullable|string|max:100',
            'contact_person' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'address' => 'nullable|string',
            'service_areas' => 'nullable|array',
            'hourly_rate' => 'nullable|numeric|min:0',
            'emergency_rate' => 'nullable|numeric|min:0',
            'rating' => 'nullable|numeric|min:0|max:5',
            'certifications' => 'nullable|array',
            'insurance_details' => 'nullable|array',
        ]);
        
        $provider = ServiceProvider::create($validated);
        
        return new ServiceProviderResource($provider);
    }

    /**
     * Get service provider details
     * 
     * Retrieve detailed information about a specific service provider including job history.
     * 
     * @urlParam id string required Service provider UUID. Example: 9d5e8c7a-1234-5678-90ab-cdef12345678
     * 
     * @response 200 scenario="Success" {
     *   "data": {
     *     "id": "9d5e8c7a-1234-5678-90ab-cdef12345678",
     *     "provider_code": "SP-001",
     *     "company_name": "ABC Plumbing Services",
     *     "service_type": "plumbing",
     *     "rating": 4.5,
     *     "total_jobs_completed": 150,
     *     "maintenance_jobs": []
     *   }
     * }
     */
    public function show(string $id)
    {
        $provider = ServiceProvider::with('maintenanceJobs')->findOrFail($id);
        
        return new ServiceProviderResource($provider);
    }

    /**
     * Update service provider
     * 
     * Update service provider information.
     * 
     * @urlParam id string required Service provider UUID. Example: 9d5e8c7a-1234-5678-90ab-cdef12345678
     * 
     * @bodyParam company_name string Company name. Example: ABC Plumbing & HVAC
     * @bodyParam service_type string Service type. Example: plumbing
     * @bodyParam license_number string License number. Example: LIC-654321
     * @bodyParam contact_person string Contact person. Example: Jane Doe
     * @bodyParam phone string Phone. Example: +971509876543
     * @bodyParam email string Email. Example: contact@abcplumbing.com
     * @bodyParam address string Address. Example: Updated address
     * @bodyParam service_areas array Service areas. Example: ["Downtown", "DIFC"]
     * @bodyParam hourly_rate numeric Hourly rate. Example: 175.00
     * @bodyParam emergency_rate numeric Emergency rate. Example: 350.00
     * @bodyParam rating numeric Rating. Example: 4.8
     * @bodyParam certifications array Certifications. Example: ["Master Plumber"]
     * @bodyParam insurance_details object Insurance. Example: {"policy_number": "POL-456"}
     * @bodyParam status string Status (active, inactive, suspended). Example: active
     * 
     * @response 200 scenario="Success" {
     *   "data": {
     *     "id": "9d5e8c7a-1234-5678-90ab-cdef12345678",
     *     "company_name": "ABC Plumbing & HVAC",
     *     "rating": 4.8
     *   }
     * }
     */
    public function update(Request $request, string $id)
    {
        $provider = ServiceProvider::findOrFail($id);
        
        $validated = $request->validate([
            'company_name' => 'sometimes|string|max:255',
            'service_type' => 'sometimes|in:plumbing,electrical,hvac,cleaning,security,landscaping,other',
            'license_number' => 'nullable|string|max:100',
            'contact_person' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|max:20',
            'email' => 'sometimes|email|max:255',
            'address' => 'nullable|string',
            'service_areas' => 'nullable|array',
            'hourly_rate' => 'nullable|numeric|min:0',
            'emergency_rate' => 'nullable|numeric|min:0',
            'rating' => 'nullable|numeric|min:0|max:5',
            'certifications' => 'nullable|array',
            'insurance_details' => 'nullable|array',
            'status' => 'nullable|in:active,inactive,suspended',
        ]);
        
        $provider->update($validated);
        
        return new ServiceProviderResource($provider);
    }

    /**
     * Delete service provider
     * 
     * Delete a service provider. Cannot delete providers with active jobs.
     * 
     * @urlParam id string required Service provider UUID. Example: 9d5e8c7a-1234-5678-90ab-cdef12345678
     * 
     * @response 200 scenario="Success" {
     *   "message": "Service provider deleted successfully."
     * }
     * 
     * @response 409 scenario="Has Active Jobs" {
     *   "message": "Cannot delete service provider with active jobs."
     * }
     */
    public function destroy(string $id)
    {
        $provider = ServiceProvider::findOrFail($id);
        
        if ($provider->maintenanceJobs()->whereIn('status', ['assigned', 'in_progress'])->exists()) {
            return response()->json([
                'message' => __('messages.cannot_delete_provider_with_active_jobs')
            ], 409);
        }
        
        $provider->delete();
        
        return response()->json([
            'message' => __('messages.service_provider_deleted')
        ]);
    }
}
