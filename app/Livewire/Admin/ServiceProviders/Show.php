<?php

namespace App\Livewire\Admin\ServiceProviders;

use App\Models\ServiceProvider;
use Livewire\Component;

class Show extends Component
{
    public ServiceProvider $provider;

    public function mount($provider)
    {
        $this->provider = ServiceProvider::with(['user', 'maintenanceJobs'])->findOrFail($provider);
    }

    public function delete()
    {
        // Check if provider has active jobs
        if ($this->provider->maintenanceJobs()->whereIn('status', ['assigned', 'accepted', 'in_progress'])->exists()) {
            session()->flash('error', 'Cannot delete service provider with active maintenance jobs.');
            return;
        }
        
        $this->provider->delete();
        session()->flash('success', 'Service provider deleted successfully.');
        return redirect()->route('admin.service-providers.index');
    }

    public function render()
    {
        return view('livewire.admin.service-providers.show');
    }
}
