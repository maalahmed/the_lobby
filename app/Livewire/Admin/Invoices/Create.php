<?php

namespace App\Livewire\Admin\Invoices;

use App\Models\Invoice;
use App\Models\LeaseContract;
use App\Models\Tenant;
use App\Models\Property;
use App\Models\PropertyUnit;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Create extends Component
{
    // Related entities
    public $contract_id = '';
    public $tenant_id = '';
    public $property_id = '';
    public $unit_id = '';

    // Invoice details
    public $type = 'rent';
    public $description = '';

    // Amounts
    public $subtotal = '';
    public $tax_amount = 0;
    public $discount_amount = 0;
    public $total_amount = 0;

    // Dates
    public $invoice_date = '';
    public $due_date = '';
    public $service_period_start = '';
    public $service_period_end = '';

    // Status and notes
    public $status = 'draft';
    public $notes = '';
    public $payment_terms = '';

    // For dropdowns
    public $contracts = [];
    public $tenants = [];
    public $properties = [];
    public $units = [];

    public function mount()
    {
        $this->contracts = LeaseContract::with(['property', 'unit', 'tenant.user'])
            ->where('status', 'active')
            ->get();
        $this->tenants = Tenant::with('user:id,name,email')->get();
        $this->properties = Property::select('id', 'name')->orderBy('name')->get();
        $this->invoice_date = now()->format('Y-m-d');
        $this->due_date = now()->addDays(30)->format('Y-m-d');
    }

    public function updatedContractId($value)
    {
        if ($value) {
            $contract = LeaseContract::with(['property', 'unit', 'tenant'])->find($value);
            if ($contract) {
                $this->tenant_id = $contract->tenant_id;
                $this->property_id = $contract->property_id;
                $this->unit_id = $contract->unit_id;
                
                // Auto-populate rent amount if type is rent
                if ($this->type === 'rent' && empty($this->subtotal)) {
                    $this->subtotal = $contract->rent_amount;
                    $this->calculateTotal();
                }
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
            if (!$this->contract_id) {
                $this->unit_id = '';
            }
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
            'notes' => 'nullable|string',
            'payment_terms' => 'nullable|string|max:255',
        ];
    }

    public function save()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            Invoice::create([
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
                'notes' => $this->notes,
                'payment_terms' => $this->payment_terms,
                'created_by' => Auth::id(),
            ]);

            DB::commit();

            session()->flash('message', 'Invoice created successfully.');
            return redirect()->route('admin.invoices.index');

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error creating invoice: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.invoices.create')
            ->layout('layouts.admin', ['title' => __('Create Invoice')]);
    }
}
