<?php

namespace App\Livewire\Admin\Payments;

use App\Models\Payment;
use App\Models\Property;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $methodFilter = '';
    public $propertyFilter = '';
    public $verificationFilter = '';

    protected $queryString = ['search', 'statusFilter', 'methodFilter', 'propertyFilter', 'verificationFilter'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingMethodFilter()
    {
        $this->resetPage();
    }

    public function updatingPropertyFilter()
    {
        $this->resetPage();
    }

    public function updatingVerificationFilter()
    {
        $this->resetPage();
    }

    public function delete($id)
    {
        $payment = Payment::findOrFail($id);
        
        // Check if payment is completed - prevent deletion
        if ($payment->status === 'completed') {
            session()->flash('error', 'Cannot delete completed payments. Please refund instead.');
            return;
        }

        $payment->delete();
        session()->flash('message', 'Payment deleted successfully.');
    }

    public function render()
    {
        $payments = Payment::query()
            ->with(['invoice', 'tenant.user', 'property', 'creator', 'verifier'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('payment_reference', 'like', '%' . $this->search . '%')
                      ->orWhereHas('tenant.user', function ($q) {
                          $q->where('name', 'like', '%' . $this->search . '%');
                      })
                      ->orWhereHas('invoice', function ($q) {
                          $q->where('invoice_number', 'like', '%' . $this->search . '%');
                      });
                });
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->when($this->methodFilter, function ($query) {
                $query->where('payment_method', $this->methodFilter);
            })
            ->when($this->propertyFilter, function ($query) {
                $query->where('property_id', $this->propertyFilter);
            })
            ->when($this->verificationFilter, function ($query) {
                $query->where('verification_status', $this->verificationFilter);
            })
            ->latest('payment_date')
            ->paginate(10);

        $properties = Property::select('id', 'name')->orderBy('name')->get();

        return view('livewire.admin.payments.index', [
            'payments' => $payments,
            'properties' => $properties,
        ]);
    }
}
