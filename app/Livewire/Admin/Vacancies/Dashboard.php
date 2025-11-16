<?php

namespace App\Livewire\Admin\Vacancies;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Property;
use App\Models\PropertyUnit;
use App\Models\LeaseContract;
use Illuminate\Support\Facades\DB;

class Dashboard extends Component
{
    use WithPagination;

    public $selectedProperty = '';
    public $selectedStatus = '';
    public $searchTerm = '';

    protected $queryString = ['selectedProperty', 'selectedStatus', 'searchTerm'];

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

    public function updateUnitStatus($unitId, $newStatus)
    {
        $unit = PropertyUnit::findOrFail($unitId);
        $unit->update(['status' => $newStatus]);

        session()->flash('message', 'Unit status updated successfully.');
    }

    public function getVacancyStats()
    {
        $totalUnits = PropertyUnit::count();
        $availableUnits = PropertyUnit::where('status', 'available')->count();
        $occupiedUnits = PropertyUnit::where('status', 'occupied')->count();
        $maintenanceUnits = PropertyUnit::where('status', 'maintenance')->count();
        $reservedUnits = PropertyUnit::where('status', 'reserved')->count();

        $occupancyRate = $totalUnits > 0 ? round(($occupiedUnits / $totalUnits) * 100, 1) : 0;
        $vacancyRate = $totalUnits > 0 ? round(($availableUnits / $totalUnits) * 100, 1) : 0;

        // Upcoming vacancies (leases ending in next 60 days)
        $upcomingVacancies = LeaseContract::where('status', 'active')
            ->whereBetween('end_date', [now(), now()->addDays(60)])
            ->count();

        return [
            'total' => $totalUnits,
            'available' => $availableUnits,
            'occupied' => $occupiedUnits,
            'maintenance' => $maintenanceUnits,
            'reserved' => $reservedUnits,
            'occupancyRate' => $occupancyRate,
            'vacancyRate' => $vacancyRate,
            'upcomingVacancies' => $upcomingVacancies,
        ];
    }

    public function render()
    {
        $properties = Property::where('status', 'active')->get();

        $units = PropertyUnit::with(['property', 'leaseContracts' => function($query) {
            $query->where('status', 'active')->latest();
        }])
        ->when($this->selectedProperty, function($query) {
            $query->where('property_id', $this->selectedProperty);
        })
        ->when($this->selectedStatus, function($query) {
            $query->where('status', $this->selectedStatus);
        })
        ->when($this->searchTerm, function($query) {
            $query->where(function($q) {
                $q->where('unit_number', 'like', '%' . $this->searchTerm . '%')
                  ->orWhereHas('property', function($prop) {
                      $prop->where('name', 'like', '%' . $this->searchTerm . '%');
                  });
            });
        })
        ->orderBy('status')
        ->orderBy('unit_number')
        ->paginate(20);

        $stats = $this->getVacancyStats();

        return view('livewire.admin.vacancies.dashboard', [
            'units' => $units,
            'properties' => $properties,
            'stats' => $stats,
        ])->layout('layouts.admin');
    }
}
