<?php

namespace App\Livewire\Tenant\Renewals;

use App\Models\LeaseRenewalOffer;
use Livewire\Component;

class Show extends Component
{
    public $offerId;
    public $offer;
    public $counter_offer_amount;
    public $tenant_response_notes;
    public $showCounterOfferForm = false;

    public function mount($offerId)
    {
        $this->offerId = $offerId;
        $this->offer = LeaseRenewalOffer::with(['currentContract.unit.property', 'landlord'])
            ->where('tenant_id', auth()->user()->tenant->id)
            ->findOrFail($offerId);

        // Mark as viewed if not already
        if (!$this->offer->viewed_at && $this->offer->status === 'sent') {
            $this->offer->update([
                'status' => 'viewed',
                'viewed_at' => now(),
            ]);
        }
    }

    public function acceptOffer()
    {
        $this->offer->update([
            'status' => 'accepted',
            'responded_at' => now(),
        ]);

        // TODO: Create new lease contract
        // TODO: Send notification to landlord

        session()->flash('message', 'Renewal offer accepted successfully!');
        return redirect()->route('tenant.renewals.index');
    }

    public function rejectOffer()
    {
        $this->validate([
            'tenant_response_notes' => 'required|string|min:10',
        ]);

        $this->offer->update([
            'status' => 'rejected',
            'responded_at' => now(),
            'tenant_response_notes' => $this->tenant_response_notes,
        ]);

        // TODO: Send notification to landlord

        session()->flash('message', 'Renewal offer rejected.');
        return redirect()->route('tenant.renewals.index');
    }

    public function submitCounterOffer()
    {
        $this->validate([
            'counter_offer_amount' => 'required|numeric|min:0',
            'tenant_response_notes' => 'nullable|string',
        ]);

        $this->offer->update([
            'status' => 'negotiating',
            'responded_at' => now(),
            'tenant_counter_offer_amount' => $this->counter_offer_amount,
            'tenant_counter_offer_terms' => $this->tenant_response_notes,
        ]);

        // TODO: Send notification to landlord

        session()->flash('message', 'Counter offer submitted successfully!');
        return redirect()->route('tenant.renewals.index');
    }

    public function render()
    {
        return view('livewire.tenant.renewals.show');
    }
}
