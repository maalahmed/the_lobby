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

class Edit extends Component
{
    public LeaseContract $contract;

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
    public $move_out_date = '';

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
    public $termination_reason = '';
    public $termination_date = '';

    // For dropdowns
    public $properties = [];
    public $units = [];
    public $tenants = [];
    public $landlords = [];

    public function mount(LeaseContract $contract)
    {
        $this->contract = $contract;

        // Populate form fields
        $this->property_id = $this->contract->property_id;
        $this->unit_id = $this->contract->unit_id;
        $this->tenant_id = $this->contract->tenant_id;
        $this->landlord_id = $this->contract->landlord_id;
        $this->start_date = $this->contract->start_date?->format('Y-m-d');
        $this->end_date = $this->contract->end_date?->format('Y-m-d');
        $this->signed_date = $this->contract->signed_date?->format('Y-m-d');
        $this->move_in_date = $this->contract->move_in_date?->format('Y-m-d');
        $this->move_out_date = $this->contract->move_out_date?->format('Y-m-d');
        $this->rent_amount = $this->contract->rent_amount;
        $this->security_deposit = $this->contract->security_deposit;
        $this->broker_commission = $this->contract->broker_commission ?? 0;
        $this->rent_frequency = $this->contract->rent_frequency;
        $this->payment_due_day = $this->contract->payment_due_day;
        $this->late_fee_amount = $this->contract->late_fee_amount ?? 0;
        $this->late_fee_grace_days = $this->contract->late_fee_grace_days ?? 5;
        $this->terms_conditions = $this->contract->terms_conditions;
        $this->special_clauses = $this->contract->special_clauses;
        $this->status = $this->contract->status;
        $this->termination_reason = $this->contract->termination_reason;
        $this->termination_date = $this->contract->termination_date?->format('Y-m-d');

        // Load dropdowns
        $this->properties = Property::select('id', 'name')->orderBy('name')->get();
        $this->tenants = Tenant::with('user:id,name,email')->get();
        $this->landlords = User::select('id', 'name', 'email')->orderBy('name')->get();
        
        // Load units for selected property
        if ($this->property_id) {
            $this->units = PropertyUnit::where('property_id', $this->property_id)
                ->select('id', 'unit_number', 'rent_amount', 'security_deposit', 'status')
                ->orderBy('unit_number')
                ->get();
        }
    }

    public function updatedPropertyId($value)
    {
        if ($value) {
            $this->units = PropertyUnit::where('property_id', $value)
                ->select('id', 'unit_number', 'rent_amount', 'security_deposit', 'status')
                ->orderBy('unit_number')
                ->get();
        } else {
            $this->units = [];
            $this->unit_id = '';
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
            'move_out_date' => 'nullable|date',
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
            'termination_reason' => 'nullable|string|max:255',
            'termination_date' => 'nullable|date',
        ];
    }

    public function update()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            $oldStatus = $this->contract->status;
            $oldUnitId = $this->contract->unit_id;

            // Update lease contract
            $this->contract->update([
                'property_id' => $this->property_id,
                'unit_id' => $this->unit_id,
                'tenant_id' => $this->tenant_id,
                'landlord_id' => $this->landlord_id,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'signed_date' => $this->signed_date ?: null,
                'move_in_date' => $this->move_in_date ?: null,
                'move_out_date' => $this->move_out_date ?: null,
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
                'termination_reason' => $this->termination_reason ?: null,
                'termination_date' => $this->termination_date ?: null,
            ]);

            // Update unit status based on contract status changes
            if ($oldStatus !== $this->status) {
                if ($this->status === 'active') {
                    PropertyUnit::where('id', $this->unit_id)->update(['status' => 'occupied']);
                } elseif (in_array($this->status, ['expired', 'terminated']) && $oldStatus === 'active') {
                    PropertyUnit::where('id', $this->unit_id)->update(['status' => 'available']);
                }
            }

            // Handle unit change
            if ($oldUnitId !== $this->unit_id) {
                PropertyUnit::where('id', $oldUnitId)->update(['status' => 'available']);
                if ($this->status === 'active') {
                    PropertyUnit::where('id', $this->unit_id)->update(['status' => 'occupied']);
                }
            }

            DB::commit();

            session()->flash('success', __('Lease contract updated successfully'));
            return redirect()->route('admin.lease-contracts.show', $this->contract->id);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update lease contract: ' . $e->getMessage());
            session()->flash('error', __('Failed to update lease contract. Please try again.'));
        }
    }

    public function render()
    {
        /** @var \Illuminate\View\View $view */
        $view = view('livewire.admin.lease-contracts.edit');
        
        return $view->layout('layouts.admin');
    }
}
