<?php

namespace App\Livewire\Admin\MaintenanceJobs;

use App\Models\MaintenanceJob;
use App\Models\MaintenanceRequest;
use App\Models\ServiceProvider;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $paymentStatusFilter = '';
    public $serviceProviderFilter = '';
    public $maintenanceRequestFilter = '';

    protected $queryString = ['search', 'statusFilter', 'paymentStatusFilter'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function delete($id)
    {
        $job = MaintenanceJob::findOrFail($id);
        
        // Check if job can be deleted (only if not completed or in_progress)
        if (in_array($job->status, ['completed', 'in_progress'])) {
            session()->flash('error', 'Cannot delete completed or in-progress jobs.');
            return;
        }

        $job->delete();
        session()->flash('message', 'Maintenance job deleted successfully.');
    }

    public function render()
    {
        $jobs = MaintenanceJob::query()
            ->with(['maintenanceRequest.property', 'maintenanceRequest.unit', 'serviceProvider.user', 'assigner'])
            ->when($this->search, function ($query) {
                $query->where(function($q) {
                    $q->where('job_number', 'like', '%' . $this->search . '%')
                      ->orWhere('work_description', 'like', '%' . $this->search . '%')
                      ->orWhereHas('serviceProvider', function($q) {
                          $q->where('company_name', 'like', '%' . $this->search . '%');
                      });
                });
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->when($this->paymentStatusFilter, function ($query) {
                $query->where('payment_status', $this->paymentStatusFilter);
            })
            ->when($this->serviceProviderFilter, function ($query) {
                $query->where('service_provider_id', $this->serviceProviderFilter);
            })
            ->when($this->maintenanceRequestFilter, function ($query) {
                $query->where('maintenance_request_id', $this->maintenanceRequestFilter);
            })
            ->latest()
            ->paginate(10);

        $serviceProviders = ServiceProvider::select('id', 'company_name')->orderBy('company_name')->get();
        $maintenanceRequests = MaintenanceRequest::select('id', 'request_number', 'title')->orderBy('request_number', 'desc')->limit(50)->get();

        return view('livewire.admin.maintenance-jobs.index', [
            'jobs' => $jobs,
            'serviceProviders' => $serviceProviders,
            'maintenanceRequests' => $maintenanceRequests,
        ])->layout('layouts.admin');
    }
}
