<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceRequest;
use App\Http\Resources\MaintenanceRequestResource;
use Illuminate\Http\Request;

/**
 * @group Maintenance Request Management
 * 
 * APIs for managing maintenance requests from tenants
 * 
 * @authenticated
 */
class MaintenanceRequestController extends Controller
{
    /**
     * List maintenance requests
     * 
     * Retrieve a paginated list of maintenance requests with filtering.
     * Tenants see their own requests. Landlords see requests for their properties.
     * 
     * @queryParam property_id string Filter by property UUID. Example: 9d5e8c7a-1111-2222-3333-444444444444
     * @queryParam unit_id string Filter by unit UUID. Example: 9d5e8c7a-2222-3333-4444-555555555555
     * @queryParam tenant_id string Filter by tenant UUID. Example: 9d5e8c7a-3333-4444-5555-666666666666
     * @queryParam category string Filter by category (plumbing, electrical, hvac, appliance, structural, other). Example: plumbing
     * @queryParam priority string Filter by priority (low, medium, high, urgent). Example: high
     * @queryParam status string Filter by status (open, in_progress, on_hold, completed, cancelled). Example: open
     * @queryParam page integer Page number. Example: 1
     * @queryParam per_page integer Items per page (max 100). Example: 20
     * 
     * @response 200 scenario="Success" {
     *   "data": [
     *     {
     *       "id": "9d5e8c7a-1234-5678-90ab-cdef12345678",
     *       "request_number": "MR-2025-001",
     *       "unit_id": "9d5e8c7a-2222-3333-4444-555555555555",
     *       "tenant_id": "9d5e8c7a-3333-4444-5555-666666666666",
     *       "category": "plumbing",
     *       "priority": "high",
     *       "status": "open",
     *       "title": "Leaking faucet in kitchen",
     *       "description": "Kitchen sink faucet is leaking continuously"
     *     }
     *   ]
     * }
     */
    public function index(Request $request)
    {
        $query = MaintenanceRequest::with(['unit', 'tenant']);
        
        if ($request->filled('property_id')) {
            $query->whereHas('unit', function($q) use ($request) {
                $q->where('property_id', $request->property_id);
            });
        }
        
        if ($request->filled('unit_id')) {
            $query->where('unit_id', $request->unit_id);
        }
        
        if ($request->filled('tenant_id')) {
            $query->where('tenant_id', $request->tenant_id);
        }
        
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $perPage = min($request->input('per_page', 20), 100);
        $requests = $query->orderBy('created_at', 'desc')->paginate($perPage);
        
        return MaintenanceRequestResource::collection($requests);
    }

    /**
     * Create a new maintenance request
     * 
     * Submit a new maintenance request for a property unit.
     * Tenants can create requests for their units. Landlords can create for any of their units.
     * 
     * @bodyParam unit_id string required Property unit UUID. Example: 9d5e8c7a-2222-3333-4444-555555555555
     * @bodyParam tenant_id string Tenant UUID (auto-filled for tenant users). Example: 9d5e8c7a-3333-4444-5555-666666666666
     * @bodyParam category string required Category (plumbing, electrical, hvac, appliance, structural, other). Example: plumbing
     * @bodyParam priority string Priority (low, medium, high, urgent). Defaults to 'medium'. Example: high
     * @bodyParam title string required Brief title of the issue. Example: Leaking faucet in kitchen
     * @bodyParam description text required Detailed description. Example: The kitchen sink faucet has been leaking for 2 days
     * @bodyParam preferred_date date Preferred service date. Example: 2025-11-15
     * @bodyParam preferred_time string Preferred time slot. Example: 09:00-12:00
     * @bodyParam access_instructions text Instructions for accessing the unit. Example: Key with building manager
     * @bodyParam attachments array Photo/document attachments. Example: ["maintenance/photo1.jpg", "maintenance/photo2.jpg"]
     * 
     * @response 201 scenario="Created" {
     *   "data": {
     *     "id": "9d5e8c7a-1234-5678-90ab-cdef12345678",
     *     "request_number": "MR-2025-001",
     *     "status": "open",
     *     "priority": "high"
     *   }
     * }
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'unit_id' => 'required|exists:property_units,id',
            'tenant_id' => 'nullable|exists:tenants,id',
            'category' => 'required|in:plumbing,electrical,hvac,appliance,structural,other',
            'priority' => 'nullable|in:low,medium,high,urgent',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'preferred_date' => 'nullable|date',
            'preferred_time' => 'nullable|string|max:50',
            'access_instructions' => 'nullable|string',
            'attachments' => 'nullable|array',
        ]);
        
        $maintenanceRequest = MaintenanceRequest::create($validated);
        
        return new MaintenanceRequestResource($maintenanceRequest);
    }

    /**
     * Get maintenance request details
     * 
     * Retrieve detailed information about a specific maintenance request.
     * 
     * @urlParam id string required Maintenance request UUID. Example: 9d5e8c7a-1234-5678-90ab-cdef12345678
     * 
     * @response 200 scenario="Success" {
     *   "data": {
     *     "id": "9d5e8c7a-1234-5678-90ab-cdef12345678",
     *     "request_number": "MR-2025-001",
     *     "title": "Leaking faucet",
     *     "description": "Kitchen sink faucet is leaking",
     *     "status": "in_progress",
     *     "unit": {
     *       "unit_number": "A-101"
     *     },
     *     "maintenance_jobs": []
     *   }
     * }
     */
    public function show(string $id)
    {
        $maintenanceRequest = MaintenanceRequest::with(['unit', 'tenant', 'maintenanceJobs'])->findOrFail($id);
        
        return new MaintenanceRequestResource($maintenanceRequest);
    }

    /**
     * Update maintenance request
     * 
     * Update maintenance request details or status.
     * Tenants can update their own requests. Landlords and admins can update any request.
     * 
     * @urlParam id string required Maintenance request UUID. Example: 9d5e8c7a-1234-5678-90ab-cdef12345678
     * 
     * @bodyParam category string Category. Example: electrical
     * @bodyParam priority string Priority. Example: urgent
     * @bodyParam title string Title. Example: Updated title
     * @bodyParam description text Description. Example: Updated description with more details
     * @bodyParam preferred_date date Preferred date. Example: 2025-11-20
     * @bodyParam preferred_time string Preferred time. Example: 14:00-17:00
     * @bodyParam access_instructions text Access instructions. Example: Use spare key
     * @bodyParam attachments array Attachments. Example: ["maintenance/updated_photo.jpg"]
     * @bodyParam status string Status (open, in_progress, on_hold, completed, cancelled). Example: in_progress
     * 
     * @response 200 scenario="Success" {
     *   "data": {
     *     "id": "9d5e8c7a-1234-5678-90ab-cdef12345678",
     *     "status": "in_progress",
     *     "priority": "urgent"
     *   }
     * }
     */
    public function update(Request $request, string $id)
    {
        $maintenanceRequest = MaintenanceRequest::findOrFail($id);
        
        $validated = $request->validate([
            'category' => 'sometimes|in:plumbing,electrical,hvac,appliance,structural,other',
            'priority' => 'nullable|in:low,medium,high,urgent',
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'preferred_date' => 'nullable|date',
            'preferred_time' => 'nullable|string|max:50',
            'access_instructions' => 'nullable|string',
            'attachments' => 'nullable|array',
            'status' => 'nullable|in:open,in_progress,on_hold,completed,cancelled',
        ]);
        
        $maintenanceRequest->update($validated);
        
        return new MaintenanceRequestResource($maintenanceRequest);
    }

    /**
     * Delete maintenance request
     * 
     * Delete a maintenance request. Only open requests with no assigned jobs can be deleted.
     * 
     * @urlParam id string required Maintenance request UUID. Example: 9d5e8c7a-1234-5678-90ab-cdef12345678
     * 
     * @response 200 scenario="Success" {
     *   "message": "Maintenance request deleted successfully."
     * }
     * 
     * @response 409 scenario="Cannot Delete" {
     *   "message": "Cannot delete maintenance request with assigned jobs."
     * }
     */
    public function destroy(string $id)
    {
        $maintenanceRequest = MaintenanceRequest::findOrFail($id);
        
        if ($maintenanceRequest->maintenanceJobs()->exists()) {
            return response()->json([
                'message' => __('messages.cannot_delete_request_with_jobs')
            ], 409);
        }
        
        $maintenanceRequest->delete();
        
        return response()->json([
            'message' => __('messages.maintenance_request_deleted')
        ]);
    }
}
