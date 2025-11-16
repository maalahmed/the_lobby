<?php

namespace App\Livewire\Admin\Properties;

use App\Models\Property;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $type = '';
    public $status = '';
    public $landlordId = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $showFilters = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'type' => ['except' => ''],
        'status' => ['except' => ''],
        'landlordId' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingType()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function updatingLandlordId()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function clearFilters()
    {
        $this->reset(['search', 'type', 'status', 'landlordId']);
        $this->resetPage();
    }

    public function deleteProperty($id)
    {
        $property = Property::findOrFail($id);
        
        // Check if property has active contracts
        if ($property->units()->whereHas('leaseContracts', function($query) {
            $query->where('status', 'active');
        })->exists()) {
            session()->flash('error', __('Cannot delete property with active contracts'));
            return;
        }

        $property->delete();
        session()->flash('success', __('Property deleted successfully'));
    }

    public function render()
    {
        $query = Property::with(['owner', 'units'])
            ->withCount(['units', 'units as occupied_units_count' => function ($query) {
                $query->where('status', 'occupied');
            }]);

        // Search
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('address_line_1', 'like', '%' . $this->search . '%')  // Fixed: column is 'address_line_1'
                    ->orWhere('city', 'like', '%' . $this->search . '%');
            });
        }

        // Filters
        if ($this->type) {
            $query->where('type', $this->type);
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        if ($this->landlordId) {
            $query->where('owner_id', $this->landlordId);  // Fixed: column is 'owner_id' not 'landlord_id'
        }

        // Sorting
        $query->orderBy($this->sortField, $this->sortDirection);

        $properties = $query->paginate(10);

        // Get landlords for filter dropdown
        $landlords = User::role('Landlord')->get();

        /** @var \Illuminate\View\View $view */
        $view = view('livewire.admin.properties.index', [
            'properties' => $properties,
            'landlords' => $landlords,
        ]);
        
        return $view]);
    }
}
