<?php

namespace App\Livewire\Admin\LeaseContracts;

use App\Models\LeaseContract;
use Livewire\Component;

class Show extends Component
{
    public LeaseContract $contract;
    public $showDeleteModal = false;

    public function mount(LeaseContract $contract)
    {
        $this->contract = $contract->load([
            'property',
            'unit',
            'tenant.user',
            'landlord',
            'creator',
            'invoices'
        ]);
    }

    public function delete()
    {
        // Check if contract has invoices
        if ($this->contract->invoices()->exists()) {
            session()->flash('error', __('Cannot delete lease contract with associated invoices'));
            $this->showDeleteModal = false;
            return;
        }

        $this->contract->delete();
        session()->flash('success', __('Lease contract deleted successfully'));
        return redirect()->route('admin.lease-contracts.index');
    }

    public function render()
    {
        /** @var \Illuminate\View\View $view */
        $view = view('livewire.admin.lease-contracts.show');
        
        return $view->layout('layouts.admin');
    }
}
