<?php

namespace App\Livewire\Admin\Units;

use App\Models\PropertyUnit;
use Livewire\Component;

class Show extends Component
{
    public PropertyUnit $unit;
    public $showDeleteModal = false;

    public function mount(PropertyUnit $unit)
    {
        // Load relationships
        $this->unit = $unit->load(['property', 'currentLease']);
    }

    public function confirmDelete()
    {
        $this->showDeleteModal = true;
    }

    public function deleteUnit()
    {
        // Check if unit has active contracts
        if ($this->unit->leaseContracts()->where('status', 'active')->exists()) {
            session()->flash('error', __('Cannot delete unit with active lease contracts'));
            $this->showDeleteModal = false;
            return;
        }

        $this->unit->delete();
        session()->flash('success', __('Unit deleted successfully'));
        
        return redirect()->route('admin.units.index');
    }

    public function render()
    {
        /** @var \Illuminate\View\View $view */
        $view = view('livewire.admin.units.show');
        
        return $view;
    }
}
