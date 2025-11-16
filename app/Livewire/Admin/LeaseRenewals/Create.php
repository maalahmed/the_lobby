<?php

namespace App\Livewire\Admin\LeaseRenewals;

use Livewire\Component;
use App\Models\LeaseContract;
use App\Models\LeaseRenewalOffer;
use Carbon\Carbon;

class Create extends Component
{
    public $leaseId;
    public $lease;

    // Offer details
    public $proposed_rent_amount;
    public $proposed_lease_duration = 12;
    public $proposed_start_date;
    public $proposed_end_date;
    public $rent_increase_percentage = 0;
    public $offer_expiry_date;
    public $offer_valid_days = 30;
    public $terms_and_conditions = '';
    public $notes = '';

    // Calculations
    public $current_rent_amount;
    public $rent_difference;

    protected $rules = [
        'proposed_rent_amount' => 'required|numeric|min:0',
        'proposed_lease_duration' => 'required|integer|min:1|max:60',
        'proposed_start_date' => 'required|date|after:today',
        'proposed_end_date' => 'required|date|after:proposed_start_date',
        'offer_expiry_date' => 'required|date|after:today|before:proposed_start_date',
        'terms_and_conditions' => 'nullable|string',
        'notes' => 'nullable|string',
    ];

    public function mount($leaseId)
    {
        $this->leaseId = $leaseId;
        $this->lease = LeaseContract::with(['tenant.user', 'unit.property', 'landlord'])
            ->findOrFail($leaseId);

        // Set defaults
        $this->current_rent_amount = $this->lease->rent_amount;
        $this->proposed_rent_amount = $this->lease->rent_amount;
        $this->proposed_start_date = $this->lease->end_date->addDay()->format('Y-m-d');
        $this->calculateEndDate();
        $this->offer_expiry_date = now()->addDays($this->offer_valid_days)->format('Y-m-d');

        $this->terms_and_conditions = "This renewal offer is subject to the following terms:\n\n"
            . "1. All terms from the original lease agreement remain in effect unless specifically modified herein.\n"
            . "2. The tenant must respond to this offer before the expiry date.\n"
            . "3. Payment terms and schedule will remain as per the original agreement.\n"
            . "4. Property condition must be maintained as per lease standards.";
    }

    public function updated($propertyName)
    {
        if ($propertyName === 'proposed_rent_amount') {
            $this->calculateRentIncrease();
        }

        if ($propertyName === 'proposed_lease_duration' || $propertyName === 'proposed_start_date') {
            $this->calculateEndDate();
        }

        if ($propertyName === 'offer_valid_days') {
            $this->offer_expiry_date = now()->addDays($this->offer_valid_days)->format('Y-m-d');
        }
    }

    public function calculateRentIncrease()
    {
        if ($this->current_rent_amount > 0) {
            $this->rent_difference = $this->proposed_rent_amount - $this->current_rent_amount;
            $this->rent_increase_percentage = round(($this->rent_difference / $this->current_rent_amount) * 100, 2);
        }
    }

    public function calculateEndDate()
    {
        if ($this->proposed_start_date && $this->proposed_lease_duration) {
            $startDate = Carbon::parse($this->proposed_start_date);
            $this->proposed_end_date = $startDate->copy()->addMonths($this->proposed_lease_duration)->format('Y-m-d');
        }
    }

    public function saveDraft()
    {
        $this->validate();

        $this->createOffer('draft');

        session()->flash('message', 'Renewal offer saved as draft successfully.');
        return redirect()->route('admin.lease-renewals.index');
    }

    public function sendOffer()
    {
        $this->validate();

        $offer = $this->createOffer('sent');

        // TODO: Send notification/email to tenant

        session()->flash('message', 'Renewal offer sent to tenant successfully.');
        return redirect()->route('admin.lease-renewals.index');
    }

    private function createOffer($status)
    {
        return LeaseRenewalOffer::create([
            'lease_contract_id' => $this->lease->id,
            'property_id' => $this->lease->property_id,
            'unit_id' => $this->lease->unit_id,
            'tenant_id' => $this->lease->tenant_id,
            'landlord_id' => $this->lease->landlord_id,
            'current_rent_amount' => $this->current_rent_amount,
            'proposed_rent_amount' => $this->proposed_rent_amount,
            'rent_increase_percentage' => $this->rent_increase_percentage,
            'proposed_lease_duration' => $this->proposed_lease_duration,
            'proposed_start_date' => $this->proposed_start_date,
            'proposed_end_date' => $this->proposed_end_date,
            'offer_expiry_date' => $this->offer_expiry_date,
            'terms_and_conditions' => $this->terms_and_conditions,
            'notes' => $this->notes,
            'status' => $status,
        ]);
    }

    public function render()
    {
        return view('livewire.admin.lease-renewals.create')->layout('layouts.admin');
    }
}
