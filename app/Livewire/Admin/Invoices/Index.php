<?php

namespace App\Livewire\Admin\Invoices;

use App\Models\Invoice;
use App\Models\LeaseContract;
use App\Models\Property;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $status = '';
    public $type = '';
    public $property_id = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    public $deleteId = null;

    protected $queryString = ['search', 'status', 'type', 'property_id'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function updatingType()
    {
        $this->resetPage();
    }

    public function updatingPropertyId()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
    }

    public function delete()
    {
        if ($this->deleteId) {
            $invoice = Invoice::find($this->deleteId);
            
            if ($invoice) {
                // Check if invoice has payments
                if ($invoice->payments()->exists()) {
                    session()->flash('error', 'Cannot delete invoice with existing payments. Please delete payments first.');
                    $this->deleteId = null;
                    return;
                }

                $invoice->delete();
                session()->flash('message', 'Invoice deleted successfully.');
            }
            
            $this->deleteId = null;
        }
    }

    public function render()
    {
        $query = Invoice::with(['contract', 'tenant.user', 'property', 'unit']);

        // Search
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('invoice_number', 'like', '%' . $this->search . '%')
                  ->orWhereHas('tenant.user', function ($q) {
                      $q->where('name', 'like', '%' . $this->search . '%');
                  })
                  ->orWhereHas('property', function ($q) {
                      $q->where('name', 'like', '%' . $this->search . '%');
                  });
            });
        }

        // Filter by status
        if ($this->status) {
            $query->where('status', $this->status);
        }

        // Filter by type
        if ($this->type) {
            $query->where('type', $this->type);
        }

        // Filter by property
        if ($this->property_id) {
            $query->where('property_id', $this->property_id);
        }

        // Sorting
        $query->orderBy($this->sortField, $this->sortDirection);

        $invoices = $query->paginate(10);
        $properties = Property::select('id', 'name')->orderBy('name')->get();

        return view('livewire.admin.invoices.index', [
            'invoices' => $invoices,
            'properties' => $properties,
        ])->layout('layouts.admin', ['title' => __('Invoices Management')]);
    }
}
