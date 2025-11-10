<?php

namespace App\Livewire\Admin\Properties;

use App\Models\Property;
use Livewire\Component;

class Show extends Component
{
    public Property $property;
    public $showDeleteModal = false;

    public function mount($id)
    {
        $this->property = Property::with(['owner', 'manager', 'units', 'amenities'])
            ->findOrFail($id);
    }

    public function confirmDelete()
    {
        $this->showDeleteModal = true;
    }

    public function deleteProperty()
    {
        // Check if property has active contracts
        if ($this->property->units()->whereHas('leaseContracts', function($query) {
            $query->where('status', 'active');
        })->exists()) {
            session()->flash('error', __('Cannot delete property with active contracts'));
            $this->showDeleteModal = false;
            return;
        }

        $this->property->delete();
        session()->flash('success', __('Property deleted successfully'));
        
        return redirect()->route('admin.properties.index');
    }

    public function render()
    {
        /** @var \Illuminate\View\View $view */
        $view = view('livewire.admin.properties.show');
        
        return $view->layout('layouts.admin', ['title' => __('Property Details')]);
    }
}
