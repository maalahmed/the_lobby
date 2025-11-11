<?php

namespace App\Livewire\Admin\Tenants;

use App\Models\Tenant;
use Livewire\Component;

class Show extends Component
{
    public Tenant $tenant;
    public $showDeleteModal = false;

    public function mount(Tenant $tenant)
    {
        $this->tenant = $tenant->load(['user', 'leaseContracts', 'currentLease']);
    }

    public function delete()
    {
        // Check if tenant has active lease contracts
        if ($this->tenant->leaseContracts()->where('status', 'active')->exists()) {
            session()->flash('error', __('Cannot delete tenant with active lease contracts'));
            return;
        }

        $this->tenant->delete();
        session()->flash('success', __('Tenant deleted successfully'));
        
        return redirect()->route('admin.tenants.index');
    }

    public function render()
    {
        /** @var \Illuminate\View\View $view */
        $view = view('livewire.admin.tenants.show');
        
        return $view->layout('layouts.admin', ['title' => __('Tenant Details')]);
    }
}
