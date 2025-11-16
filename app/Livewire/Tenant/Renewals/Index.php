<?php

namespace App\Livewire\Tenant\Renewals;

use App\Models\LeaseRenewalOffer;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $statusFilter = 'all';

    public function render()
    {
        $tenant = auth()->user()->tenant;

        $query = LeaseRenewalOffer::with(['currentContract.unit.property', 'landlord'])
            ->where('tenant_id', $tenant->id);

        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        $offers = $query->latest()->paginate(10);

        $stats = [
            'pending' => LeaseRenewalOffer::where('tenant_id', $tenant->id)
                ->whereIn('status', ['sent', 'viewed'])
                ->where('offer_expiry_date', '>=', now())
                ->count(),
            'accepted' => LeaseRenewalOffer::where('tenant_id', $tenant->id)
                ->where('status', 'accepted')
                ->count(),
            'expired' => LeaseRenewalOffer::where('tenant_id', $tenant->id)
                ->where('status', 'sent')
                ->where('offer_expiry_date', '<', now())
                ->count(),
        ];

        return view('livewire.tenant.renewals.index', [
            'offers' => $offers,
            'stats' => $stats,
        ])->layout('layouts.tenant');
    }
}
