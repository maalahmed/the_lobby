<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LeaseContract;
use App\Http\Resources\LeaseContractResource;
use Illuminate\Http\Request;

/**
 * @group Lease Contract Management
 * 
 * APIs for managing lease contracts between landlords and tenants
 * 
 * @authenticated
 */
class LeaseContractController extends Controller
{
    /**
     * List lease contracts
     * 
     * Retrieve a paginated list of lease contracts with filtering.
     * Landlords see their properties' contracts. Tenants see their own contracts.
     * 
     * @queryParam property_id string Filter by property UUID. Example: 9d5e8c7a-1111-2222-3333-444444444444
     * @queryParam unit_id string Filter by unit UUID. Example: 9d5e8c7a-2222-3333-4444-555555555555
     * @queryParam tenant_id string Filter by tenant UUID. Example: 9d5e8c7a-3333-4444-5555-666666666666
     * @queryParam status string Filter by status (draft, active, expired, terminated, renewed). Example: active
     * @queryParam page integer Page number. Example: 1
     * @queryParam per_page integer Items per page (max 100). Example: 20
     * 
     * @response 200 scenario="Success" {
     *   "data": [
     *     {
     *       "id": "9d5e8c7a-1234-5678-90ab-cdef12345678",
     *       "contract_number": "LC-2025-001",
     *       "unit_id": "9d5e8c7a-2222-3333-4444-555555555555",
     *       "tenant_id": "9d5e8c7a-3333-4444-5555-666666666666",
     *       "start_date": "2025-01-01",
     *       "end_date": "2025-12-31",
     *       "monthly_rent": 2500.00,
     *       "security_deposit": 5000.00,
     *       "status": "active"
     *     }
     *   ]
     * }
     */
    public function index(Request $request)
    {
        $query = LeaseContract::with(['unit', 'tenant']);
        
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
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $perPage = min($request->input('per_page', 20), 100);
        $contracts = $query->paginate($perPage);
        
        return LeaseContractResource::collection($contracts);
    }

    /**
     * Create a new lease contract
     * 
     * Create a new lease agreement between landlord and tenant for a specific unit.
     * 
     * @bodyParam unit_id string required Property unit UUID. Example: 9d5e8c7a-2222-3333-4444-555555555555
     * @bodyParam tenant_id string required Tenant UUID. Example: 9d5e8c7a-3333-4444-5555-666666666666
     * @bodyParam start_date date required Contract start date. Example: 2025-01-01
     * @bodyParam end_date date required Contract end date. Example: 2025-12-31
     * @bodyParam monthly_rent numeric required Monthly rent amount. Example: 2500.00
     * @bodyParam security_deposit numeric Security deposit amount. Example: 5000.00
     * @bodyParam payment_day_of_month integer Day of month for payment (1-31). Example: 1
     * @bodyParam late_fee_percentage numeric Late payment fee percentage. Example: 5.00
     * @bodyParam grace_period_days integer Grace period in days. Example: 5
     * @bodyParam terms_and_conditions text Contract terms. Example: Standard rental agreement terms...
     * @bodyParam special_clauses text Special contract clauses. Example: Pets allowed with approval
     * @bodyParam utilities_included array Utilities included. Example: ["water", "electricity"]
     * @bodyParam maintenance_responsibility string Who handles maintenance (landlord, tenant, shared). Example: landlord
     * @bodyParam renewal_terms object Renewal terms. Example: {"auto_renew": true, "notice_period_days": 60}
     * 
     * @response 201 scenario="Created" {
     *   "data": {
     *     "id": "9d5e8c7a-1234-5678-90ab-cdef12345678",
     *     "contract_number": "LC-2025-001",
     *     "status": "draft",
     *     "monthly_rent": 2500.00
     *   }
     * }
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'unit_id' => 'required|exists:property_units,id',
            'tenant_id' => 'required|exists:tenants,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'monthly_rent' => 'required|numeric|min:0',
            'security_deposit' => 'nullable|numeric|min:0',
            'payment_day_of_month' => 'nullable|integer|min:1|max:31',
            'late_fee_percentage' => 'nullable|numeric|min:0|max:100',
            'grace_period_days' => 'nullable|integer|min:0',
            'terms_and_conditions' => 'nullable|string',
            'special_clauses' => 'nullable|string',
            'utilities_included' => 'nullable|array',
            'maintenance_responsibility' => 'nullable|in:landlord,tenant,shared',
            'renewal_terms' => 'nullable|array',
        ]);
        
        $contract = LeaseContract::create($validated);
        
        return new LeaseContractResource($contract);
    }

    /**
     * Get lease contract details
     * 
     * Retrieve detailed information about a specific lease contract.
     * 
     * @urlParam id string required Contract UUID. Example: 9d5e8c7a-1234-5678-90ab-cdef12345678
     * 
     * @response 200 scenario="Success" {
     *   "data": {
     *     "id": "9d5e8c7a-1234-5678-90ab-cdef12345678",
     *     "contract_number": "LC-2025-001",
     *     "start_date": "2025-01-01",
     *     "end_date": "2025-12-31",
     *     "monthly_rent": 2500.00,
     *     "unit": {
     *       "unit_number": "A-101"
     *     },
     *     "tenant": {
     *       "tenant_code": "TEN-001"
     *     }
     *   }
     * }
     */
    public function show(string $id)
    {
        $contract = LeaseContract::with(['unit.property', 'tenant.user'])->findOrFail($id);
        
        return new LeaseContractResource($contract);
    }

    /**
     * Update lease contract
     * 
     * Update lease contract details. Only draft contracts can be freely edited.
     * Active contracts have restrictions on what can be modified.
     * 
     * @urlParam id string required Contract UUID. Example: 9d5e8c7a-1234-5678-90ab-cdef12345678
     * 
     * @bodyParam start_date date Contract start date. Example: 2025-01-15
     * @bodyParam end_date date Contract end date. Example: 2026-01-14
     * @bodyParam monthly_rent numeric Monthly rent amount. Example: 2600.00
     * @bodyParam security_deposit numeric Security deposit. Example: 5200.00
     * @bodyParam payment_day_of_month integer Payment day. Example: 5
     * @bodyParam late_fee_percentage numeric Late fee percentage. Example: 7.00
     * @bodyParam grace_period_days integer Grace period. Example: 3
     * @bodyParam terms_and_conditions text Contract terms. Example: Updated terms...
     * @bodyParam special_clauses text Special clauses. Example: Updated clauses...
     * @bodyParam status string Status (draft, active, expired, terminated, renewed). Example: active
     * 
     * @response 200 scenario="Success" {
     *   "data": {
     *     "id": "9d5e8c7a-1234-5678-90ab-cdef12345678",
     *     "monthly_rent": 2600.00,
     *     "status": "active"
     *   }
     * }
     */
    public function update(Request $request, string $id)
    {
        $contract = LeaseContract::findOrFail($id);
        
        $validated = $request->validate([
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date|after:start_date',
            'monthly_rent' => 'sometimes|numeric|min:0',
            'security_deposit' => 'nullable|numeric|min:0',
            'payment_day_of_month' => 'nullable|integer|min:1|max:31',
            'late_fee_percentage' => 'nullable|numeric|min:0|max:100',
            'grace_period_days' => 'nullable|integer|min:0',
            'terms_and_conditions' => 'nullable|string',
            'special_clauses' => 'nullable|string',
            'utilities_included' => 'nullable|array',
            'maintenance_responsibility' => 'nullable|in:landlord,tenant,shared',
            'renewal_terms' => 'nullable|array',
            'status' => 'nullable|in:draft,active,expired,terminated,renewed',
        ]);
        
        $contract->update($validated);
        
        return new LeaseContractResource($contract);
    }

    /**
     * Delete lease contract
     * 
     * Delete a lease contract. Only draft contracts can be deleted.
     * Active or historical contracts cannot be deleted for audit purposes.
     * 
     * @urlParam id string required Contract UUID. Example: 9d5e8c7a-1234-5678-90ab-cdef12345678
     * 
     * @response 200 scenario="Success" {
     *   "message": "Lease contract deleted successfully."
     * }
     * 
     * @response 409 scenario="Cannot Delete Active Contract" {
     *   "message": "Only draft contracts can be deleted."
     * }
     */
    public function destroy(string $id)
    {
        $contract = LeaseContract::findOrFail($id);
        
        if ($contract->status !== 'draft') {
            return response()->json([
                'message' => __('messages.only_draft_contracts_can_be_deleted')
            ], 409);
        }
        
        $contract->delete();
        
        return response()->json([
            'message' => __('messages.contract_deleted')
        ]);
    }
}
