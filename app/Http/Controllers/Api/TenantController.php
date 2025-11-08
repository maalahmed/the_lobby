<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Http\Resources\TenantResource;
use Illuminate\Http\Request;

/**
 * @group Tenant Management
 * 
 * APIs for managing tenants and their profiles
 * 
 * @authenticated
 */
class TenantController extends Controller
{
    /**
     * List tenants
     * 
     * Retrieve a paginated list of tenants with filtering options.
     * Landlords see only their property tenants. Admins see all tenants.
     * 
     * @queryParam property_id string Filter by property UUID. Example: 9d5e8c7a-1111-2222-3333-444444444444
     * @queryParam status string Filter by status (active, inactive, pending). Example: active
     * @queryParam search string Search by name, email, or phone. Example: john
     * @queryParam page integer Page number. Example: 1
     * @queryParam per_page integer Items per page (max 100). Example: 20
     * 
     * @response 200 scenario="Success" {
     *   "data": [
     *     {
     *       "id": "9d5e8c7a-1234-5678-90ab-cdef12345678",
     *       "user_id": "9d5e8c7a-5555-6666-7777-888888888888",
     *       "tenant_code": "TEN-001",
     *       "national_id": "123456789",
     *       "passport_number": "AB1234567",
     *       "nationality": "UAE",
     *       "date_of_birth": "1990-01-15",
     *       "employment_status": "employed",
     *       "employer_name": "Tech Corp",
     *       "status": "active",
     *       "user": {
     *         "name": "John Doe",
     *         "email": "john@example.com"
     *       }
     *     }
     *   ]
     * }
     */
    public function index(Request $request)
    {
        $query = Tenant::with('user');
        
        if ($request->filled('property_id')) {
            $query->whereHas('leaseContracts', function($q) use ($request) {
                $q->whereHas('unit', function($q2) use ($request) {
                    $q2->where('property_id', $request->property_id);
                });
            });
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%")
                       ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhere('phone', 'like', "%{$search}%")
                ->orWhere('tenant_code', 'like', "%{$search}%");
            });
        }
        
        $perPage = min($request->input('per_page', 20), 100);
        $tenants = $query->paginate($perPage);
        
        return TenantResource::collection($tenants);
    }

    /**
     * Create a new tenant
     * 
     * Register a new tenant profile. Typically used when onboarding a tenant for a lease.
     * 
     * @bodyParam user_id string required User UUID (must have 'tenant' role). Example: 9d5e8c7a-5555-6666-7777-888888888888
     * @bodyParam national_id string National ID number. Example: 123456789
     * @bodyParam passport_number string Passport number. Example: AB1234567
     * @bodyParam nationality string Nationality. Example: UAE
     * @bodyParam date_of_birth date Date of birth. Example: 1990-01-15
     * @bodyParam phone string required Phone number. Example: +971501234567
     * @bodyParam employment_status string Employment status (employed, self_employed, unemployed, student, retired). Example: employed
     * @bodyParam employer_name string Employer name. Example: Tech Corp
     * @bodyParam employer_phone string Employer phone. Example: +97143001234
     * @bodyParam monthly_income numeric Monthly income. Example: 15000.00
     * @bodyParam emergency_contact object Emergency contact details. Example: {"name": "Jane Doe", "relationship": "Sister", "phone": "+971501111111"}
     * @bodyParam preferences object Tenant preferences. Example: {"communication_language": "en", "payment_reminder": true}
     * @bodyParam documents array Array of document paths. Example: ["documents/passport.pdf", "documents/visa.pdf"]
     * 
     * @response 201 scenario="Created" {
     *   "data": {
     *     "id": "9d5e8c7a-1234-5678-90ab-cdef12345678",
     *     "user_id": "9d5e8c7a-5555-6666-7777-888888888888",
     *     "tenant_code": "TEN-001",
     *     "status": "active"
     *   }
     * }
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'national_id' => 'nullable|string|max:50',
            'passport_number' => 'nullable|string|max:50',
            'nationality' => 'nullable|string|max:50',
            'date_of_birth' => 'nullable|date',
            'phone' => 'required|string|max:20',
            'employment_status' => 'nullable|in:employed,self_employed,unemployed,student,retired',
            'employer_name' => 'nullable|string|max:255',
            'employer_phone' => 'nullable|string|max:20',
            'monthly_income' => 'nullable|numeric|min:0',
            'emergency_contact' => 'nullable|array',
            'preferences' => 'nullable|array',
            'documents' => 'nullable|array',
        ]);
        
        $tenant = Tenant::create($validated);
        
        return new TenantResource($tenant);
    }

    /**
     * Get tenant details
     * 
     * Retrieve detailed information about a specific tenant including lease history.
     * 
     * @urlParam id string required Tenant UUID. Example: 9d5e8c7a-1234-5678-90ab-cdef12345678
     * 
     * @response 200 scenario="Success" {
     *   "data": {
     *     "id": "9d5e8c7a-1234-5678-90ab-cdef12345678",
     *     "tenant_code": "TEN-001",
     *     "phone": "+971501234567",
     *     "employment_status": "employed",
     *     "user": {
     *       "name": "John Doe",
     *       "email": "john@example.com"
     *     },
     *     "lease_contracts": []
     *   }
     * }
     */
    public function show(string $id)
    {
        $tenant = Tenant::with(['user', 'leaseContracts'])->findOrFail($id);
        
        return new TenantResource($tenant);
    }

    /**
     * Update tenant
     * 
     * Update tenant profile information.
     * 
     * @urlParam id string required Tenant UUID. Example: 9d5e8c7a-1234-5678-90ab-cdef12345678
     * 
     * @bodyParam national_id string National ID number. Example: 987654321
     * @bodyParam passport_number string Passport number. Example: CD9876543
     * @bodyParam nationality string Nationality. Example: India
     * @bodyParam date_of_birth date Date of birth. Example: 1990-01-15
     * @bodyParam phone string Phone number. Example: +971509876543
     * @bodyParam employment_status string Employment status. Example: self_employed
     * @bodyParam employer_name string Employer name. Example: Own Business
     * @bodyParam employer_phone string Employer phone. Example: +97143009876
     * @bodyParam monthly_income numeric Monthly income. Example: 20000.00
     * @bodyParam emergency_contact object Emergency contact. Example: {"name": "Jane Doe", "phone": "+971501111111"}
     * @bodyParam preferences object Preferences. Example: {"communication_language": "ar"}
     * @bodyParam documents array Documents. Example: ["documents/updated_passport.pdf"]
     * @bodyParam status string Status (active, inactive, pending). Example: active
     * 
     * @response 200 scenario="Success" {
     *   "data": {
     *     "id": "9d5e8c7a-1234-5678-90ab-cdef12345678",
     *     "phone": "+971509876543",
     *     "monthly_income": 20000.00
     *   }
     * }
     */
    public function update(Request $request, string $id)
    {
        $tenant = Tenant::findOrFail($id);
        
        $validated = $request->validate([
            'national_id' => 'nullable|string|max:50',
            'passport_number' => 'nullable|string|max:50',
            'nationality' => 'nullable|string|max:50',
            'date_of_birth' => 'nullable|date',
            'phone' => 'sometimes|string|max:20',
            'employment_status' => 'nullable|in:employed,self_employed,unemployed,student,retired',
            'employer_name' => 'nullable|string|max:255',
            'employer_phone' => 'nullable|string|max:20',
            'monthly_income' => 'nullable|numeric|min:0',
            'emergency_contact' => 'nullable|array',
            'preferences' => 'nullable|array',
            'documents' => 'nullable|array',
            'status' => 'nullable|in:active,inactive,pending',
        ]);
        
        $tenant->update($validated);
        
        return new TenantResource($tenant);
    }

    /**
     * Delete tenant
     * 
     * Delete a tenant profile. Cannot delete tenants with active lease contracts.
     * 
     * @urlParam id string required Tenant UUID. Example: 9d5e8c7a-1234-5678-90ab-cdef12345678
     * 
     * @response 200 scenario="Success" {
     *   "message": "Tenant deleted successfully."
     * }
     * 
     * @response 409 scenario="Has Active Contracts" {
     *   "message": "Cannot delete tenant with active lease contracts."
     * }
     */
    public function destroy(string $id)
    {
        $tenant = Tenant::findOrFail($id);
        
        if ($tenant->leaseContracts()->where('status', 'active')->exists()) {
            return response()->json([
                'message' => __('messages.tenant_has_active_contracts')
            ], 409);
        }
        
        $tenant->delete();
        
        return response()->json([
            'message' => __('messages.tenant_deleted')
        ]);
    }
}
