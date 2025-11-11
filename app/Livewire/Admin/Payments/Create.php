<?php

namespace App\Livewire\Admin\Payments;

use App\Models\Payment;
use App\Models\Invoice;
use App\Models\Tenant;
use App\Models\Property;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Create extends Component
{
    public $invoice_id = '';
    public $tenant_id = '';
    public $property_id = '';
    public $amount = 0;
    public $currency = 'SAR';
    public $payment_method = 'cash';
    public $payment_date;
    public $status = 'pending';
    public $verification_status = 'not_required';
    
    // Gateway fields
    public $gateway_name = '';
    public $gateway_transaction_id = '';
    
    // Bank/Check fields
    public $bank_name = '';
    public $check_number = '';
    public $bank_reference = '';
    
    // Additional
    public $notes = '';
    public $receipt_url = '';

    public function mount()
    {
        $this->payment_date = date('Y-m-d');
    }

    public function updatedInvoiceId($value)
    {
        if ($value) {
            $invoice = Invoice::with(['tenant', 'property'])->find($value);
            if ($invoice) {
                $this->tenant_id = $invoice->tenant_id;
                $this->property_id = $invoice->property_id;
                
                // Calculate remaining balance
                $remainingBalance = $invoice->total_amount - $invoice->paid_amount;
                $this->amount = max(0, $remainingBalance);
            }
        }
    }

    public function save()
    {
        $validated = $this->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'property_id' => 'required|exists:properties,id',
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'required|string|max:3',
            'payment_method' => 'required|in:cash,bank_transfer,check,card,online,mobile_payment',
            'payment_date' => 'required|date',
            'status' => 'required|in:pending,processing,completed,failed,cancelled,refunded',
            'verification_status' => 'required|in:not_required,pending,verified,rejected',
            'invoice_id' => 'nullable|exists:invoices,id',
            'gateway_name' => 'nullable|string|max:50',
            'gateway_transaction_id' => 'nullable|string',
            'bank_name' => 'nullable|string',
            'check_number' => 'nullable|string|max:50',
            'bank_reference' => 'nullable|string',
            'notes' => 'nullable|string',
            'receipt_url' => 'nullable|url|max:500',
        ]);

        $validated['created_by'] = Auth::id();
        
        // Set processed_at if status is completed
        if ($this->status === 'completed') {
            $validated['processed_at'] = now();
        }

        $payment = Payment::create($validated);

        // Update invoice paid amount if linked
        if ($payment->invoice_id) {
            $invoice = Invoice::find($payment->invoice_id);
            $invoice->paid_amount += $payment->amount;
            
            // Update invoice status based on payment
            if ($invoice->paid_amount >= $invoice->total_amount) {
                $invoice->status = 'paid';
                $invoice->paid_at = now();
            } elseif ($invoice->paid_amount > 0) {
                $invoice->status = 'partial_paid';
            }
            
            $invoice->save();
        }

        session()->flash('message', 'Payment created successfully.');
        return redirect()->route('admin.payments.show', $payment);
    }

    public function render()
    {
        $invoices = Invoice::with(['tenant.user', 'property'])
            ->whereIn('status', ['sent', 'viewed', 'partial_paid', 'overdue'])
            ->latest()
            ->get();
        
        $tenants = Tenant::with('user')->get();
        $properties = Property::select('id', 'name')->orderBy('name')->get();

        return view('livewire.admin.payments.create', [
            'invoices' => $invoices,
            'tenants' => $tenants,
            'properties' => $properties,
        ])->layout('layouts.admin', ['title' => 'Create Payment']);
    }
}
