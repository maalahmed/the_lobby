<?php

namespace App\Livewire\Admin\MaintenanceRequests;

use App\Models\MaintenanceRequest;
use App\Models\Property;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $priorityFilter = '';
    public $categoryFilter = '';
    public $propertyFilter = '';

    protected $queryString = ['search', 'statusFilter', 'priorityFilter', 'categoryFilter', 'propertyFilter'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingPriorityFilter()
    {
        $this->resetPage();
    }

    public function updatingCategoryFilter()
    {
        $this->resetPage();
    }

    public function updatingPropertyFilter()
    {
        $this->resetPage();
    }

    public function delete($id)
    {
        $request = MaintenanceRequest::findOrFail($id);
        
        // Check if request has jobs - prevent deletion
        if ($request->jobs()->exists()) {
            session()->flash('error', 'Cannot delete maintenance request with associated jobs.');
            return;
        }

        $request->delete();
        session()->flash('message', 'Maintenance request deleted successfully.');
    }

    public function render()
    {
        $requests = MaintenanceRequest::query()
            ->with(['property', 'unit', 'requester', 'tenant.user', 'assignee'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('request_number', 'like', '%' . $this->search . '%')
                      ->orWhere('title', 'like', '%' . $this->search . '%')
                      ->orWhereHas('property', function ($q) {
                          $q->where('name', 'like', '%' . $this->search . '%');
                      });
                });
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->when($this->priorityFilter, function ($query) {
                $query->where('priority', $this->priorityFilter);
            })
            ->when($this->categoryFilter, function ($query) {
                $query->where('category', $this->categoryFilter);
            })
            ->when($this->propertyFilter, function ($query) {
                $query->where('property_id', $this->propertyFilter);
            })
            ->latest('created_at')
            ->paginate(10);

        $properties = Property::select('id', 'name')->orderBy('name')->get();

        return view('livewire.admin.maintenance-requests.index', [
            'requests' => $requests,
            'properties' => $properties,
        ]);
    }
}
