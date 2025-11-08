<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Http\Resources\PaymentResource;
use Illuminate\Http\Request;

/**
 * @group Payment Management
 * 
 * APIs for managing payments and transaction records
 * 
 * @authenticated
 */
class PaymentController extends Controller
{
    /**
     * List payments
     * 
     * Retrieve a paginated list of payments with filtering.
     * Landlords see their properties' payments. Tenants see their own payments.
     * 
     * @queryParam invoice_id string Filter by invoice UUID. Example: 9d5e8c7a-1111-2222-3333-444444444444
     * @queryParam tenant_id string Filter by tenant UUID. Example: 9d5e8c7a-2222-3333-4444-555555555555
     * @queryParam payment_method string Filter by method (cash, bank_transfer, credit_card, cheque, online). Example: bank_transfer
     * @queryParam status string Filter by status (pending, completed, failed, refunded). Example: completed
     * @queryParam from_date date Filter from this date. Example: 2025-01-01
     * @queryParam to_date date Filter to this date. Example: 2025-12-31
     * @queryParam page integer Page number. Example: 1
     * @queryParam per_page integer Items per page (max 100). Example: 20
     * 
     * @response 200 scenario="Success" {
     *   "data": [
     *     {
     *       "id": "9d5e8c7a-1234-5678-90ab-cdef12345678",
     *       "payment_number": "PAY-2025-001",
     *       "invoice_id": "9d5e8c7a-1111-2222-3333-444444444444",
     *       "amount": 2500.00,
     *       "payment_date": "2025-01-25",
     *       "payment_method": "bank_transfer",
     *       "status": "completed"
     *     }
     *   ]
     * }
     */
    public function index(Request $request)
    {
        $query = Payment::with('invoice');
        
        if ($request->filled('invoice_id')) {
            $query->where('invoice_id', $request->invoice_id);
        }
        
        if ($request->filled('tenant_id')) {
            $query->whereHas('invoice.contract', function($q) use ($request) {
                $q->where('tenant_id', $request->tenant_id);
            });
        }
        
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('from_date')) {
            $query->where('payment_date', '>=', $request->from_date);
        }
        
        if ($request->filled('to_date')) {
            $query->where('payment_date', '<=', $request->to_date);
        }
        
        $perPage = min($request->input('per_page', 20), 100);
        $payments = $query->orderBy('payment_date', 'desc')->paginate($perPage);
        
        return PaymentResource::collection($payments);
    }

    /**
     * Record a new payment
     * 
     * Record a payment against an invoice.
     * 
     * @bodyParam invoice_id string required Invoice UUID. Example: 9d5e8c7a-1111-2222-3333-444444444444
     * @bodyParam amount numeric required Payment amount. Example: 2500.00
     * @bodyParam payment_date date required Payment date. Example: 2025-01-25
     * @bodyParam payment_method string required Payment method (cash, bank_transfer, credit_card, cheque, online). Example: bank_transfer
     * @bodyParam transaction_reference string Transaction reference/ID. Example: TXN123456789
     * @bodyParam notes text Payment notes. Example: Paid via online banking
     * @bodyParam received_by string User who received the payment. Example: John Admin
     * 
     * @response 201 scenario="Created" {
     *   "data": {
     *     "id": "9d5e8c7a-1234-5678-90ab-cdef12345678",
     *     "payment_number": "PAY-2025-001",
     *     "amount": 2500.00,
     *     "status": "completed"
     *   }
     * }
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'payment_method' => 'required|in:cash,bank_transfer,credit_card,cheque,online',
            'transaction_reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'received_by' => 'nullable|string|max:255',
        ]);
        
        $payment = Payment::create($validated);
        
        // Update invoice paid amount
        $invoice = $payment->invoice;
        $invoice->increment('paid_amount', $payment->amount);
        
        // Update invoice status if fully paid
        if ($invoice->paid_amount >= $invoice->amount) {
            $invoice->update(['status' => 'paid']);
        }
        
        return new PaymentResource($payment);
    }

    /**
     * Get payment details
     * 
     * Retrieve detailed information about a specific payment.
     * 
     * @urlParam id string required Payment UUID. Example: 9d5e8c7a-1234-5678-90ab-cdef12345678
     * 
     * @response 200 scenario="Success" {
     *   "data": {
     *     "id": "9d5e8c7a-1234-5678-90ab-cdef12345678",
     *     "payment_number": "PAY-2025-001",
     *     "amount": 2500.00,
     *     "payment_date": "2025-01-25",
     *     "payment_method": "bank_transfer",
     *     "status": "completed",
     *     "invoice": {
     *       "invoice_number": "INV-2025-001"
     *     }
     *   }
     * }
     */
    public function show(string $id)
    {
        $payment = Payment::with('invoice')->findOrFail($id);
        
        return new PaymentResource($payment);
    }

    /**
     * Update payment
     * 
     * Update payment details. Only pending payments can be modified.
     * 
     * @urlParam id string required Payment UUID. Example: 9d5e8c7a-1234-5678-90ab-cdef12345678
     * 
     * @bodyParam amount numeric Payment amount. Example: 2600.00
     * @bodyParam payment_date date Payment date. Example: 2025-01-26
     * @bodyParam payment_method string Payment method. Example: credit_card
     * @bodyParam transaction_reference string Transaction reference. Example: TXN987654321
     * @bodyParam notes text Notes. Example: Updated payment details
     * @bodyParam status string Status (pending, completed, failed, refunded). Example: completed
     * 
     * @response 200 scenario="Success" {
     *   "data": {
     *     "id": "9d5e8c7a-1234-5678-90ab-cdef12345678",
     *     "amount": 2600.00,
     *     "status": "completed"
     *   }
     * }
     */
    public function update(Request $request, string $id)
    {
        $payment = Payment::findOrFail($id);
        
        $validated = $request->validate([
            'amount' => 'sometimes|numeric|min:0',
            'payment_date' => 'sometimes|date',
            'payment_method' => 'sometimes|in:cash,bank_transfer,credit_card,cheque,online',
            'transaction_reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'received_by' => 'nullable|string|max:255',
            'status' => 'nullable|in:pending,completed,failed,refunded',
        ]);
        
        $payment->update($validated);
        
        return new PaymentResource($payment);
    }

    /**
     * Delete payment
     * 
     * Delete a payment record. Only pending payments can be deleted.
     * Completed payments cannot be deleted for audit purposes.
     * 
     * @urlParam id string required Payment UUID. Example: 9d5e8c7a-1234-5678-90ab-cdef12345678
     * 
     * @response 200 scenario="Success" {
     *   "message": "Payment deleted successfully."
     * }
     * 
     * @response 409 scenario="Cannot Delete" {
     *   "message": "Only pending payments can be deleted."
     * }
     */
    public function destroy(string $id)
    {
        $payment = Payment::findOrFail($id);
        
        if ($payment->status === 'completed') {
            return response()->json([
                'message' => __('messages.completed_payments_cannot_be_deleted')
            ], 409);
        }
        
        $payment->delete();
        
        return response()->json([
            'message' => __('messages.payment_deleted')
        ]);
    }
}
