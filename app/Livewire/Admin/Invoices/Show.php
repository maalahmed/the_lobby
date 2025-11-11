<?php

namespace App\Livewire\Admin\Invoices;

use App\Models\Invoice;
use Livewire\Component;

class Show extends Component
{
    public Invoice $invoice;

    public $deleteId = null;

    public function mount(Invoice $invoice)
    {
        $this->invoice = $invoice->load([
            'contract',
            'tenant.user',
            'property',
            'unit',
            'creator',
            'payments'
        ]);
    }

    public function confirmDelete()
    {
        $this->deleteId = $this->invoice->id;
    }

    public function delete()
    {
        if ($this->deleteId) {
            // Check if invoice has payments
            if ($this->invoice->payments()->exists()) {
                session()->flash('error', 'Cannot delete invoice with existing payments. Please delete payments first.');
                $this->deleteId = null;
                return;
            }

            $this->invoice->delete();
            session()->flash('message', 'Invoice deleted successfully.');
            return redirect()->route('admin.invoices.index');
        }
    }

    public function render()
    {
        return view('livewire.admin.invoices.show')
            ->layout('layouts.admin', ['title' => __('Invoice Details')]);
    }
}
