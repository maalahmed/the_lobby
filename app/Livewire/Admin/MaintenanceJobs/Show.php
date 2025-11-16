<?php

namespace App\Livewire\Admin\MaintenanceJobs;

use App\Models\MaintenanceJob;
use Livewire\Component;

class Show extends Component
{
    public MaintenanceJob $job;

    public function mount(MaintenanceJob $job)
    {
        $this->job = $job->load([
            'maintenanceRequest.property',
            'maintenanceRequest.unit',
            'maintenanceRequest.tenant.user',
            'serviceProvider.user',
            'assigner'
        ]);
    }

    public function delete()
    {
        // Check if job can be deleted
        if (in_array($this->job->status, ['completed', 'in_progress'])) {
            session()->flash('error', 'Cannot delete completed or in-progress jobs.');
            return;
        }

        $this->job->delete();
        session()->flash('message', 'Maintenance job deleted successfully.');
        return redirect()->route('admin.maintenance-jobs.index');
    }

    public function render()
    {
        return view('livewire.admin.maintenance-jobs.show')->layout('layouts.admin');
    }
}
