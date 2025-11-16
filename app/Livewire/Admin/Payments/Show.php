<?php

namespace App\Livewire\Admin\Payments;

use App\Models\Payment;
use Livewire\Component;

class Show extends Component
{
    public Payment $payment;

    public function mount(Payment $payment)
    {
        $this->payment = $payment->load(['invoice', 'tenant.user', 'property', 'creator', 'verifier']);
    }

    public function delete()
    {
        // Check if payment is completed
        if ($this->payment->status === 'completed') {
            session()->flash('error', 'Cannot delete completed payments. Please refund instead.');
            return;
        }

        $this->payment->delete();
        session()->flash('message', 'Payment deleted successfully.');
        return redirect()->route('admin.payments.index');
    }

    public function render()
    {
        return view('livewire.admin.payments.show')->layout('layouts.admin');
    }
}
