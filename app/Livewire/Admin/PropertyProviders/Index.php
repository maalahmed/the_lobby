<?php

namespace App\Livewire\Admin\PropertyProviders;

use App\Models\PropertyProvider;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $status = '';
    public $tier = '';

    protected $queryString = ['search', 'status', 'tier'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = PropertyProvider::query();

        if ($this->search) {
            $query->where('company_name', 'like', '%' . $this->search . '%')
                  ->orWhere('contact_email', 'like', '%' . $this->search . '%');
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        if ($this->tier) {
            $query->where('subscription_tier', $this->tier);
        }

        $propertyProviders = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('livewire.admin.property-providers.index', [
            'propertyProviders' => $propertyProviders,
        ])->layout('layouts.admin');
    }
}
