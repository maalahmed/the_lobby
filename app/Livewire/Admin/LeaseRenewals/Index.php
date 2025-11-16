<?php

namespace App\Livewire\Admin\LeaseRenewals;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\LeaseContract;
use App\Models\LeaseRenewalOffer;
use App\Models\Property;
use Carbon\Carbon;

class Index extends Component
{
    use WithPagination;

    public $selectedProperty = '';
    public $selectedStatus = '';
    public $searchTerm = '';
    public $showExpiringOnly = false;

    protected $queryString = ['selectedProperty', 'selectedStatus', 'searchTerm', 'showExpiringOnly'];

    public function updatingSearchTerm()
    {
        $this->resetPage();
    }

    public function updatingSelectedProperty()
    {
        $this->resetPage();
    }

    public function updatingSelectedStatus()
    {
        $this->resetPage();
    }

    public function getExpiringLeasesStats()
    {
        $baseQuery = LeaseContract::where('status', 'active')
            ->where('end_date', '>=', now());

        return [
            'expiring_30_days' => (clone $baseQuery)->whereBetween('end_date', [now(), now()->addDays(30)])->count(),
            'expiring_60_days' => (clone $baseQuery)->whereBetween('end_date', [now()->addDays(31), now()->addDays(60)])->count(),
            'expiring_90_days' => (clone $baseQuery)->whereBetween('end_date', [now()->addDays(61), now()->addDays(90)])->count(),
        ];
    }

    public function getRenewalOffersStats()
    {
        return [
            'pending' => LeaseRenewalOffer::whereIn('status', ['sent', 'viewed', 'negotiating'])->count(),
            'accepted' => LeaseRenewalOffer::where('status', 'accepted')->count(),
            'rejected' => LeaseRenewalOffer::where('status', 'rejected')->count(),
            'expired' => LeaseRenewalOffer::where('status', 'expired')->count(),
        ];
    }

    public function render()
    {
        $properties = Property::orderBy('name')->get();

        // Get expiring leases
        $expiringQuery = LeaseContract::with(['unit.property', 'tenant.user'])
            ->where('status', 'active')
            ->where('end_date', '>=', now())
            ->where('end_date', '<=', now()->addDays(90))
            ->orderBy('end_date');

        if ($this->selectedProperty) {
            $expiringQuery->where('property_id', $this->selectedProperty);
        }

        if ($this->searchTerm) {
            $expiringQuery->whereHas('tenant.user', function ($q) {
                $q->where('name', 'like', '%' . $this->searchTerm . '%')
                  ->orWhere('email', 'like', '%' . $this->searchTerm . '%');
            })->orWhereHas('unit', function ($q) {
                $q->where('unit_number', 'like', '%' . $this->searchTerm . '%');
            });
        }

        $expiringLeases = $expiringQuery->paginate(20);

        // Get renewal offers
        $offersQuery = LeaseRenewalOffer::with(['currentContract.unit.property', 'tenant.user'])
            ->orderBy('created_at', 'desc');

        if ($this->selectedStatus) {
            $offersQuery->where('status', $this->selectedStatus);
        }

        if ($this->selectedProperty) {
            $offersQuery->where('property_id', $this->selectedProperty);
        }

        $renewalOffers = $offersQuery->limit(10)->get();

        $stats = [
            'expiring' => $this->getExpiringLeasesStats(),
            'offers' => $this->getRenewalOffersStats(),
        ];

        return view('livewire.admin.lease-renewals.index', [
            'expiringLeases' => $expiringLeases,
            'renewalOffers' => $renewalOffers,
            'properties' => $properties,
            'stats' => $stats,
        ]);
    }
}
