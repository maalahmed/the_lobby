<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Http\Resources\InvoiceResource;
use Illuminate\Http\Request;

/**
 * @group Invoice Management
 * 
 * APIs for managing invoices and billing
 * 
 * @authenticated
 */
class InvoiceController extends Controller
{
    /**
     * List invoices
     * 
     * Retrieve a paginated list of invoices with filtering.
     * Landlords see their properties' invoices. Tenants see their own invoices.
     * 
     * @queryParam contract_id string Filter by lease contract UUID. Example: 9d5e8c7a-1111-2222-3333-444444444444
     * @queryParam tenant_id string Filter by tenant UUID. Example: 9d5e8c7a-2222-3333-4444-555555555555
     * @queryParam type string Filter by type (rent, utility, maintenance, other). Example: rent
     * @queryParam status string Filter by status (draft, sent, paid, overdue, cancelled). Example: paid
     * @queryParam from_date date Filter invoices from this date. Example: 2025-01-01
     * @queryParam to_date date Filter invoices to this date. Example: 2025-12-31
     * @queryParam page integer Page number. Example: 1
     * @queryParam per_page integer Items per page (max 100). Example: 20
     * 
     * @response 200 scenario="Success" {
     *   "data": [
     *     {
     *       "id": "9d5e8c7a-1234-5678-90ab-cdef12345678",
     *       "invoice_number": "INV-2025-001",
     *       "contract_id": "9d5e8c7a-1111-2222-3333-444444444444",
     *       "type": "rent",
     *       "amount": 2500.00,
     *       "due_date": "2025-02-01",
     *       "status": "paid",
     *       "paid_amount": 2500.00
     *     }
     *   ]
     * }
     */
    public function index(Request $request)
    {
        $query = Invoice::with('contract');
        
        if ($request->filled('contract_id')) {
            $query->where('contract_id', $request->contract_id);
        }
        
        if ($request->filled('tenant_id')) {
            $query->whereHas('contract', function($q) use ($request) {
                $q->where('tenant_id', $request->tenant_id);
            });
        }
        
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('from_date')) {
            $query->where('issue_date', '>=', $request->from_date);
        }
        
        if ($request->filled('to_date')) {
            $query->where('issue_date', '<=', $request->to_date);
        }
        
        $perPage = min($request->input('per_page', 20), 100);
        $invoices = $query->orderBy('issue_date', 'desc')->paginate($perPage);
        
        return InvoiceResource::collection($invoices);
    }

    /**
     * Create a new invoice
     * 
     * Generate a new invoice for a lease contract.
     * 
     * @bodyParam contract_id string required Lease contract UUID. Example: 9d5e8c7a-1111-2222-3333-444444444444
     * @bodyParam type string required Invoice type (rent, utility, maintenance, other). Example: rent
     * @bodyParam description string Invoice description. Example: Monthly rent for February 2025
     * @bodyParam amount numeric required Invoice amount. Example: 2500.00
     * @bodyParam issue_date date required Invoice issue date. Example: 2025-01-15
     * @bodyParam due_date date required Payment due date. Example: 2025-02-01
     * @bodyParam tax_amount numeric Tax amount if applicable. Example: 125.00
     * @bodyParam line_items array Detailed line items. Example: [{"description": "Rent", "amount": 2500.00}]
     * @bodyParam notes text Additional notes. Example: Please pay before due date to avoid late fees
     * 
     * @response 201 scenario="Created" {
     *   "data": {
     *     "id": "9d5e8c7a-1234-5678-90ab-cdef12345678",
     *     "invoice_number": "INV-2025-001",
     *     "amount": 2500.00,
     *     "status": "draft"
     *   }
     * }
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'contract_id' => 'required|exists:lease_contracts,id',
            'type' => 'required|in:rent,utility,maintenance,other',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'issue_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:issue_date',
            'tax_amount' => 'nullable|numeric|min:0',
            'line_items' => 'nullable|array',
            'notes' => 'nullable|string',
        ]);
        
        $invoice = Invoice::create($validated);
        
        return new InvoiceResource($invoice);
    }

    /**
     * Get invoice details
     * 
     * Retrieve detailed information about a specific invoice.
     * 
     * @urlParam id string required Invoice UUID. Example: 9d5e8c7a-1234-5678-90ab-cdef12345678
     * 
     * @response 200 scenario="Success" {
     *   "data": {
     *     "id": "9d5e8c7a-1234-5678-90ab-cdef12345678",
     *     "invoice_number": "INV-2025-001",
     *     "type": "rent",
     *     "amount": 2500.00,
     *     "due_date": "2025-02-01",
     *     "status": "paid",
     *     "contract": {
     *       "contract_number": "LC-2025-001"
     *     },
     *     "payments": []
     *   }
     * }
     */
    public function show(string $id)
    {
        $invoice = Invoice::with(['contract', 'payments'])->findOrFail($id);
        
        return new InvoiceResource($invoice);
    }

    /**
     * Update invoice
     * 
     * Update invoice details. Only draft invoices can be freely edited.
     * 
     * @urlParam id string required Invoice UUID. Example: 9d5e8c7a-1234-5678-90ab-cdef12345678
     * 
     * @bodyParam type string Invoice type. Example: utility
     * @bodyParam description string Description. Example: Updated description
     * @bodyParam amount numeric Amount. Example: 2600.00
     * @bodyParam issue_date date Issue date. Example: 2025-01-20
     * @bodyParam due_date date Due date. Example: 2025-02-05
     * @bodyParam tax_amount numeric Tax amount. Example: 130.00
     * @bodyParam line_items array Line items. Example: [{"description": "Rent", "amount": 2600.00}]
     * @bodyParam notes text Notes. Example: Updated payment terms
     * @bodyParam status string Status (draft, sent, paid, overdue, cancelled). Example: sent
     * 
     * @response 200 scenario="Success" {
     *   "data": {
     *     "id": "9d5e8c7a-1234-5678-90ab-cdef12345678",
     *     "amount": 2600.00,
     *     "status": "sent"
     *   }
     * }
     */
    public function update(Request $request, string $id)
    {
        $invoice = Invoice::findOrFail($id);
        
        $validated = $request->validate([
            'type' => 'sometimes|in:rent,utility,maintenance,other',
            'description' => 'nullable|string',
            'amount' => 'sometimes|numeric|min:0',
            'issue_date' => 'sometimes|date',
            'due_date' => 'sometimes|date',
            'tax_amount' => 'nullable|numeric|min:0',
            'line_items' => 'nullable|array',
            'notes' => 'nullable|string',
            'status' => 'nullable|in:draft,sent,paid,overdue,cancelled',
        ]);
        
        $invoice->update($validated);
        
        return new InvoiceResource($invoice);
    }

    /**
     * Delete invoice
     * 
     * Delete an invoice. Only draft invoices can be deleted.
     * Paid or sent invoices cannot be deleted for audit purposes.
     * 
     * @urlParam id string required Invoice UUID. Example: 9d5e8c7a-1234-5678-90ab-cdef12345678
     * 
     * @response 200 scenario="Success" {
     *   "message": "Invoice deleted successfully."
     * }
     * 
     * @response 409 scenario="Cannot Delete" {
     *   "message": "Only draft invoices can be deleted."
     * }
     */
    public function destroy(string $id)
    {
        $invoice = Invoice::findOrFail($id);
        
        if ($invoice->status !== 'draft') {
            return response()->json([
                'message' => __('messages.only_draft_invoices_can_be_deleted')
            ], 409);
        }
        
        $invoice->delete();
        
        return response()->json([
            'message' => __('messages.invoice_deleted')
        ]);
    }
}
