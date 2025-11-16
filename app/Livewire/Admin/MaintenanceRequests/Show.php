<?php

namespace App\Livewire\Admin\MaintenanceRequests;

use App\Models\MaintenanceRequest;
use Livewire\Component;

class Show extends Component
{
    public MaintenanceRequest $request;

    public function mount(MaintenanceRequest $request)
    {
        $this->request = $request->load([
            'property', 
            'unit', 
            'requester', 
            'tenant.user', 
            'assignee', 
            'costApprover',
            'jobs'
        ]);
    }

    public function delete()
    {
        // Check if request has jobs
        if ($this->request->jobs()->exists()) {
            session()->flash('error', 'Cannot delete maintenance request with associated jobs.');
            return;
        }

        $this->request->delete();
        session()->flash('message', 'Maintenance request deleted successfully.');
        return redirect()->route('admin.maintenance-requests.index');
    }

    public function render()
    {
        return view('livewire.admin.maintenance-requests.show');
    }
}
