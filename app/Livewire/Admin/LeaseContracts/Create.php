<?php

namespace App\Livewire\Admin\LeaseContracts;

use App\Models\LeaseContract;
use App\Models\Property;
use App\Models\PropertyUnit;
use App\Models\Tenant;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class Create extends Component
{
    // Contract parties
    public $property_id = '';
    public $unit_id = '';
    public $tenant_id = '';
    public $landlord_id = '';

    // Contract dates
    public $start_date = '';
    public $end_date = '';
    public $signed_date = '';
    public $move_in_date = '';

    // Financial terms
    public $rent_amount = '';
    public $security_deposit = '';
    public $broker_commission = 0;
    public $rent_frequency = 'monthly';

    // Payment terms
    public $payment_due_day = 1;
    public $late_fee_amount = 0;
    public $late_fee_grace_days = 5;

    // Contract terms
    public $terms_conditions = '';
    public $special_clauses = '';

    // Status
    public $status = 'draft';

    // For dropdowns
    public $properties = [];
    public $units = [];
    public $tenants = [];
    public $landlords = [];

    public function mount()
    {
        $this->properties = Property::select('id', 'name')->orderBy('name')->get();
        $this->tenants = Tenant::with('user:id,name,email')->get();
        $this->landlords = User::select('id', 'name', 'email')->orderBy('name')->get();
    }

    public function updatedPropertyId($value)
    {
        if ($value) {
            $this->units = PropertyUnit::where('property_id', $value)
                ->select('id', 'unit_number', 'rent_amount', 'security_deposit', 'status')
                ->orderBy('unit_number')
                ->get();
            $this->unit_id = '';
        } else {
            $this->units = [];
            $this->unit_id = '';
        }
    }

    public function updatedUnitId($value)
    {
        if ($value) {
            $unit = PropertyUnit::find($value);
            if ($unit && empty($this->rent_amount)) {
                $this->rent_amount = $unit->rent_amount;
                $this->security_deposit = $unit->security_deposit ?? ($unit->rent_amount * 1); // Default 1 month rent
            }
        }
    }

    protected function rules()
    {
        return [
            'property_id' => 'required|exists:properties,id',
            'unit_id' => 'required|exists:property_units,id',
            'tenant_id' => 'required|exists:tenants,id',
            'landlord_id' => 'required|exists:users,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'signed_date' => 'nullable|date',
            'move_in_date' => 'nullable|date',
            'rent_amount' => 'required|numeric|min:0',
            'security_deposit' => 'required|numeric|min:0',
            'broker_commission' => 'nullable|numeric|min:0',
            'rent_frequency' => 'required|in:monthly,quarterly,semi_annual,annual',
            'payment_due_day' => 'required|integer|min:1|max:31',
            'late_fee_amount' => 'nullable|numeric|min:0',
            'late_fee_grace_days' => 'nullable|integer|min:0|max:30',
            'terms_conditions' => 'nullable|string',
            'special_clauses' => 'nullable|string',
            'status' => 'required|in:draft,pending_signature,active,expired,terminated,renewed',
        ];
    }

    public function save()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            // Create lease contract
            $contract = LeaseContract::create([
                'property_id' => $this->property_id,
                'unit_id' => $this->unit_id,
                'tenant_id' => $this->tenant_id,
                'landlord_id' => $this->landlord_id,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'signed_date' => $this->signed_date ?: null,
                'move_in_date' => $this->move_in_date ?: null,
                'rent_amount' => $this->rent_amount,
                'security_deposit' => $this->security_deposit,
                'broker_commission' => $this->broker_commission ?: 0,
                'rent_frequency' => $this->rent_frequency,
                'payment_due_day' => $this->payment_due_day,
                'late_fee_amount' => $this->late_fee_amount ?: 0,
                'late_fee_grace_days' => $this->late_fee_grace_days ?? 5,
                'terms_conditions' => $this->terms_conditions ?: null,
                'special_clauses' => $this->special_clauses ?: null,
                'status' => $this->status,
                'created_by' => Auth::id(),
            ]);

            // Update unit status if contract is active
            if ($this->status === 'active') {
                PropertyUnit::where('id', $this->unit_id)->update(['status' => 'occupied']);
            }

            DB::commit();

            session()->flash('success', __('Lease contract created successfully'));
            return redirect()->route('admin.lease-contracts.show', $contract->id);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create lease contract: ' . $e->getMessage());
            session()->flash('error', __('Failed to create lease contract. Please try again.'));
        }
    }

    public function render()
    {
        /** @var \Illuminate\View\View $view */
        $view = view('livewire.admin.lease-contracts.create');
        
        return $view->layout('layouts.admin');
    }
}
