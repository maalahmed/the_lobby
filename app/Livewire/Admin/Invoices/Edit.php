<?php

namespace App\Livewire\Admin\Invoices;

use App\Models\Invoice;
use App\Models\LeaseContract;
use App\Models\Tenant;
use App\Models\Property;
use App\Models\PropertyUnit;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class Edit extends Component
{
    public Invoice $invoice;

    // Related entities
    public $contract_id;
    public $tenant_id;
    public $property_id;
    public $unit_id;

    // Invoice details
    public $type;
    public $description;

    // Amounts
    public $subtotal;
    public $tax_amount;
    public $discount_amount;
    public $total_amount;

    // Dates
    public $invoice_date;
    public $due_date;
    public $service_period_start;
    public $service_period_end;

    // Status and notes
    public $status;
    public $paid_amount;
    public $notes;
    public $payment_terms;

    // For dropdowns
    public $contracts = [];
    public $tenants = [];
    public $properties = [];
    public $units = [];

    public function mount(Invoice $invoice)
    {
        $this->invoice = $invoice;
        
        // Populate fields
        $this->contract_id = $this->invoice->contract_id;
        $this->tenant_id = $this->invoice->tenant_id;
        $this->property_id = $this->invoice->property_id;
        $this->unit_id = $this->invoice->unit_id;
        $this->type = $this->invoice->type;
        $this->description = $this->invoice->description;
        $this->subtotal = $this->invoice->subtotal;
        $this->tax_amount = $this->invoice->tax_amount;
        $this->discount_amount = $this->invoice->discount_amount;
        $this->total_amount = $this->invoice->total_amount;
        $this->invoice_date = $this->invoice->invoice_date->format('Y-m-d');
        $this->due_date = $this->invoice->due_date->format('Y-m-d');
        $this->service_period_start = $this->invoice->service_period_start?->format('Y-m-d');
        $this->service_period_end = $this->invoice->service_period_end?->format('Y-m-d');
        $this->status = $this->invoice->status;
        $this->paid_amount = $this->invoice->paid_amount;
        $this->notes = $this->invoice->notes;
        $this->payment_terms = $this->invoice->payment_terms;

        // Load dropdowns
        $this->contracts = LeaseContract::with(['property', 'unit', 'tenant.user'])
            ->where('status', 'active')
            ->get();
        $this->tenants = Tenant::with('user:id,name,email')->get();
        $this->properties = Property::select('id', 'name')->orderBy('name')->get();
        
        // Load units for selected property
        if ($this->property_id) {
            $this->units = PropertyUnit::where('property_id', $this->property_id)
                ->select('id', 'unit_number', 'rent_amount', 'status')
                ->orderBy('unit_number')
                ->get();
        }
    }

    public function updatedContractId($value)
    {
        if ($value) {
            $contract = LeaseContract::with(['property', 'unit', 'tenant'])->find($value);
            if ($contract) {
                $this->tenant_id = $contract->tenant_id;
                $this->property_id = $contract->property_id;
                $this->unit_id = $contract->unit_id;
            }
        }
    }

    public function updatedPropertyId($value)
    {
        if ($value) {
            $this->units = PropertyUnit::where('property_id', $value)
                ->select('id', 'unit_number', 'rent_amount', 'status')
                ->orderBy('unit_number')
                ->get();
        } else {
            $this->units = [];
            if (!$this->contract_id) {
                $this->unit_id = '';
            }
        }
    }

    public function updatedSubtotal()
    {
        $this->calculateTotal();
    }

    public function updatedTaxAmount()
    {
        $this->calculateTotal();
    }

    public function updatedDiscountAmount()
    {
        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $subtotal = floatval($this->subtotal ?: 0);
        $tax = floatval($this->tax_amount ?: 0);
        $discount = floatval($this->discount_amount ?: 0);
        
        $this->total_amount = $subtotal + $tax - $discount;
    }

    protected function rules()
    {
        return [
            'tenant_id' => 'required|exists:tenants,id',
            'property_id' => 'required|exists:properties,id',
            'unit_id' => 'nullable|exists:property_units,id',
            'contract_id' => 'nullable|exists:lease_contracts,id',
            'type' => 'required|in:rent,deposit,late_fee,utility,maintenance,service,other',
            'description' => 'nullable|string',
            'subtotal' => 'required|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'service_period_start' => 'nullable|date',
            'service_period_end' => 'nullable|date|after_or_equal:service_period_start',
            'status' => 'required|in:draft,sent,viewed,partial_paid,paid,overdue,cancelled,refunded',
            'paid_amount' => 'nullable|numeric|min:0|max:' . $this->total_amount,
            'notes' => 'nullable|string',
            'payment_terms' => 'nullable|string|max:255',
        ];
    }

    public function update()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            $this->invoice->update([
                'contract_id' => $this->contract_id ?: null,
                'tenant_id' => $this->tenant_id,
                'property_id' => $this->property_id,
                'unit_id' => $this->unit_id ?: null,
                'type' => $this->type,
                'description' => $this->description,
                'subtotal' => $this->subtotal,
                'tax_amount' => $this->tax_amount ?: 0,
                'discount_amount' => $this->discount_amount ?: 0,
                'total_amount' => $this->total_amount,
                'invoice_date' => $this->invoice_date,
                'due_date' => $this->due_date,
                'service_period_start' => $this->service_period_start ?: null,
                'service_period_end' => $this->service_period_end ?: null,
                'status' => $this->status,
                'paid_amount' => $this->paid_amount ?: 0,
                'notes' => $this->notes,
                'payment_terms' => $this->payment_terms,
            ]);

            DB::commit();

            session()->flash('message', 'Invoice updated successfully.');
            return redirect()->route('admin.invoices.show', $this->invoice);

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error updating invoice: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.invoices.edit')
            ->layout('layouts.admin', ['title' => __('Edit Invoice')]);
    }
}
