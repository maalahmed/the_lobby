<?php

namespace App\Livewire\Admin\Tenants;

use App\Models\Tenant;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $status = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $showDeleteModal = false;
    public $deletingTenantId = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatus()
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
        $this->reset(['search', 'status']);
        $this->resetPage();
    }

    public function confirmDelete($id)
    {
        $this->deletingTenantId = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        if (!$this->deletingTenantId) {
            return;
        }

        $tenant = Tenant::findOrFail($this->deletingTenantId);
        
        // Check if tenant has active lease contracts
        if ($tenant->leaseContracts()->where('status', 'active')->exists()) {
            session()->flash('error', __('Cannot delete tenant with active lease contracts'));
            $this->showDeleteModal = false;
            $this->deletingTenantId = null;
            return;
        }

        $tenant->delete();
        session()->flash('success', __('Tenant deleted successfully'));
        $this->showDeleteModal = false;
        $this->deletingTenantId = null;
    }

    public function render()
    {
        $query = Tenant::with(['user'])
            ->withCount(['leaseContracts', 'leaseContracts as active_leases_count' => function ($query) {
                $query->where('status', 'active');
            }]);

        // Search
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('tenant_code', 'like', '%' . $this->search . '%')
                    ->orWhere('occupation', 'like', '%' . $this->search . '%')
                    ->orWhere('employer', 'like', '%' . $this->search . '%')
                    ->orWhereHas('user', function($q) {
                        $q->where('name', 'like', '%' . $this->search . '%')
                          ->orWhere('email', 'like', '%' . $this->search . '%')
                          ->orWhere('phone', 'like', '%' . $this->search . '%');
                    });
            });
        }

        // Filter by status
        if ($this->status) {
            $query->where('status', $this->status);
        }

        // Sorting
        $query->orderBy($this->sortField, $this->sortDirection);

        $tenants = $query->paginate(10);

        /** @var \Illuminate\View\View $view */
        $view = view('livewire.admin.tenants.index', [
            'tenants' => $tenants,
        ]);
        
        return $view->layout('layouts.admin', ['title' => __('Tenants Management')]);
    }
}
