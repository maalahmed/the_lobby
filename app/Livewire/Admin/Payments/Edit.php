<?php

namespace App\Livewire\Admin\Payments;

use App\Models\Payment;
use App\Models\Invoice;
use App\Models\Tenant;
use App\Models\Property;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Edit extends Component
{
    public Payment $payment;
    
    public $invoice_id;
    public $tenant_id;
    public $property_id;
    public $amount;
    public $currency;
    public $payment_method;
    public $payment_date;
    public $status;
    public $verification_status;
    
    // Gateway fields
    public $gateway_name;
    public $gateway_transaction_id;
    
    // Bank/Check fields
    public $bank_name;
    public $check_number;
    public $bank_reference;
    
    // Additional
    public $notes;
    public $receipt_url;
    
    // Refund fields
    public $refunded_amount;
    public $refund_reason;

    public function mount(Payment $payment)
    {
        $this->payment = $payment;
        
        $this->invoice_id = $payment->invoice_id;
        $this->tenant_id = $payment->tenant_id;
        $this->property_id = $payment->property_id;
        $this->amount = $payment->amount;
        $this->currency = $payment->currency;
        $this->payment_method = $payment->payment_method;
        $this->payment_date = $payment->payment_date->format('Y-m-d');
        $this->status = $payment->status;
        $this->verification_status = $payment->verification_status;
        $this->gateway_name = $payment->gateway_name;
        $this->gateway_transaction_id = $payment->gateway_transaction_id;
        $this->bank_name = $payment->bank_name;
        $this->check_number = $payment->check_number;
        $this->bank_reference = $payment->bank_reference;
        $this->notes = $payment->notes;
        $this->receipt_url = $payment->receipt_url;
        $this->refunded_amount = $payment->refunded_amount;
        $this->refund_reason = $payment->refund_reason;
    }

    public function updatedInvoiceId($value)
    {
        if ($value) {
            $invoice = Invoice::with(['tenant', 'property'])->find($value);
            if ($invoice) {
                $this->tenant_id = $invoice->tenant_id;
                $this->property_id = $invoice->property_id;
            }
        }
    }

    public function update()
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
            'refunded_amount' => 'nullable|numeric|min:0|max:' . $this->amount,
            'refund_reason' => 'nullable|string',
        ]);

        $oldAmount = $this->payment->amount;
        $oldInvoiceId = $this->payment->invoice_id;
        
        // Track status changes
        $statusChanged = $this->payment->status !== $this->status;
        $verificationChanged = $this->payment->verification_status !== $this->verification_status;
        
        // Update timestamps based on status changes
        if ($statusChanged && $this->status === 'completed') {
            $validated['processed_at'] = now();
        }
        
        if ($verificationChanged && $this->verification_status === 'verified') {
            $validated['verified_at'] = now();
            $validated['verified_by'] = Auth::id();
        }
        
        if ($this->refunded_amount > 0 && $this->payment->refunded_amount != $this->refunded_amount) {
            $validated['refunded_at'] = now();
            $validated['status'] = 'refunded';
        }

        $this->payment->update($validated);

        // Update old invoice if changed
        if ($oldInvoiceId && $oldInvoiceId != $this->invoice_id) {
            $oldInvoice = Invoice::find($oldInvoiceId);
            if ($oldInvoice) {
                $oldInvoice->paid_amount -= $oldAmount;
                $oldInvoice->save();
            }
        }

        // Update new invoice paid amount
        if ($this->invoice_id) {
            $invoice = Invoice::find($this->invoice_id);
            if ($invoice) {
                // Recalculate total paid amount for this invoice
                $totalPaid = Payment::where('invoice_id', $invoice->id)
                    ->where('status', 'completed')
                    ->sum('amount');
                
                $invoice->paid_amount = $totalPaid;
                
                // Update invoice status
                if ($invoice->paid_amount >= $invoice->total_amount) {
                    $invoice->status = 'paid';
                    $invoice->paid_at = now();
                } elseif ($invoice->paid_amount > 0) {
                    $invoice->status = 'partial_paid';
                } else {
                    $invoice->status = 'sent';
                }
                
                $invoice->save();
            }
        }

        session()->flash('message', 'Payment updated successfully.');
        return redirect()->route('admin.payments.show', $this->payment);
    }

    public function render()
    {
        $invoices = Invoice::with(['tenant.user', 'property'])
            ->whereIn('status', ['sent', 'viewed', 'partial_paid', 'overdue', 'paid'])
            ->latest()
            ->get();
        
        $tenants = Tenant::with('user')->get();
        $properties = Property::select('id', 'name')->orderBy('name')->get();

        return view('livewire.admin.payments.edit', [
            'invoices' => $invoices,
            'tenants' => $tenants,
            'properties' => $properties,
        ])->layout('layouts.admin', ['title' => 'Edit Payment']);
    }
}
