<?php

namespace App\Livewire\Admin\ServiceProviders;

use App\Models\ServiceProvider;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function delete($id)
    {
        $provider = ServiceProvider::findOrFail($id);
        
        // Check if provider has active jobs
        if ($provider->maintenanceJobs()->whereIn('status', ['assigned', 'accepted', 'in_progress'])->exists()) {
            session()->flash('error', 'Cannot delete service provider with active maintenance jobs.');
            return;
        }
        
        $provider->delete();
        session()->flash('success', 'Service provider deleted successfully.');
    }

    public function render()
    {
        $providers = ServiceProvider::query()
            ->with(['user'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('provider_code', 'like', '%' . $this->search . '%')
                        ->orWhere('company_name', 'like', '%' . $this->search . '%')
                        ->orWhere('primary_contact_name', 'like', '%' . $this->search . '%')
                        ->orWhere('primary_contact_email', 'like', '%' . $this->search . '%')
                        ->orWhere('primary_contact_phone', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->latest()
            ->paginate(10);

        return view('livewire.admin.service-providers.index', [
            'providers' => $providers,
        ]);
    }
}
