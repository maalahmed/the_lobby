<?php

namespace App\Livewire\Admin\LeaseContracts;

use App\Models\LeaseContract;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $status = '';
    public $property_id = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $showDeleteModal = false;
    public $deletingContractId = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => ''],
        'property_id' => ['except' => ''],
    ];

    public function updatingSearch()
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
        $this->reset(['search', 'status', 'property_id']);
        $this->resetPage();
    }

    public function confirmDelete($id)
    {
        $this->deletingContractId = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        if (!$this->deletingContractId) {
            return;
        }

        $contract = LeaseContract::findOrFail($this->deletingContractId);
        
        // Check if contract has invoices
        if ($contract->invoices()->exists()) {
            session()->flash('error', __('Cannot delete lease contract with associated invoices'));
            $this->showDeleteModal = false;
            $this->deletingContractId = null;
            return;
        }

        $contract->delete();
        session()->flash('success', __('Lease contract deleted successfully'));
        $this->showDeleteModal = false;
        $this->deletingContractId = null;
    }

    public function render()
    {
        $query = LeaseContract::with(['property', 'unit', 'tenant.user', 'landlord']);

        // Search
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('contract_number', 'like', '%' . $this->search . '%')
                    ->orWhereHas('tenant.user', function($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('property', function($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('unit', function($q) {
                        $q->where('unit_number', 'like', '%' . $this->search . '%');
                    });
            });
        }

        // Filter by status
        if ($this->status) {
            $query->where('status', $this->status);
        }

        // Filter by property
        if ($this->property_id) {
            $query->where('property_id', $this->property_id);
        }

        // Sorting
        $query->orderBy($this->sortField, $this->sortDirection);

        $contracts = $query->paginate(10);

        // Get properties for filter dropdown
        $properties = \App\Models\Property::select('id', 'name')->orderBy('name')->get();

        /** @var \Illuminate\View\View $view */
        $view = view('livewire.admin.lease-contracts.index', [
            'contracts' => $contracts,
            'properties' => $properties,
        ]);
        
        return $view->layout('layouts.admin');
    }
}
