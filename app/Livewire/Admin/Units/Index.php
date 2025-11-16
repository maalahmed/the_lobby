<?php

namespace App\Livewire\Admin\Units;

use App\Models\PropertyUnit;
use App\Models\Property;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $type = '';
    public $status = '';
    public $propertyId = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $showFilters = false;
    public $showDeleteModal = false;
    public $deletingUnitId = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'type' => ['except' => ''],
        'status' => ['except' => ''],
        'propertyId' => ['except' => ''],
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

    public function updatingPropertyId()
    {
        $this->resetPage();
    }

    public function sortBy(string $field)
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
        $this->reset(['search', 'type', 'status', 'propertyId']);
        $this->resetPage();
    }

    public function confirmDelete($id)
    {
        $this->deletingUnitId = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        if (!$this->deletingUnitId) {
            return;
        }

        $unit = PropertyUnit::findOrFail($this->deletingUnitId);
        
        // Check if unit has active contracts
        if ($unit->leaseContracts()->where('status', 'active')->exists()) {
            session()->flash('error', __('Cannot delete unit with active lease contracts'));
            $this->showDeleteModal = false;
            $this->deletingUnitId = null;
            return;
        }

        $unit->delete();
        session()->flash('success', __('Unit deleted successfully'));
        $this->showDeleteModal = false;
        $this->deletingUnitId = null;
    }

    public function render()
    {
        $query = PropertyUnit::with(['property'])
            ->withCount(['leaseContracts', 'leaseContracts as active_leases_count' => function ($query) {
                $query->where('status', 'active');
            }]);

        // Search
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('unit_number', 'like', '%' . $this->search . '%')
                    ->orWhereHas('property', function($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    });
            });
        }

        // Filters
        if ($this->type) {
            $query->where('type', $this->type);
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        if ($this->propertyId) {
            $query->where('property_id', $this->propertyId);
        }

        // Sorting
        $query->orderBy($this->sortField, $this->sortDirection);

        $units = $query->paginate(10);

        // Get properties for filter dropdown
        $properties = Property::select('id', 'name')->get();

        /** @var \Illuminate\View\View $view */
        $view = view('livewire.admin.units.index', [
            'units' => $units,
            'properties' => $properties,
        ]);
        
        return $view;
    }
}
