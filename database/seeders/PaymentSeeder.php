<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Payment;
use App\Models\Invoice;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $paidInvoices = Invoice::where('status', 'paid')->get();

        foreach ($paidInvoices as $invoice) {
            Payment::create([
                'invoice_id' => $invoice->id,
                'tenant_id' => $invoice->tenant_id,
                'amount' => $invoice->total_amount,
                'payment_date' => $invoice->paid_date,
                'payment_method' => $this->getRandomPaymentMethod(),
                'transaction_id' => 'TXN-' . strtoupper(uniqid()),
                'status' => 'completed',
                'notes' => 'Payment for ' . $invoice->invoice_number,
            ]);
        }
    }

    private function getRandomPaymentMethod(): string
    {
        $methods = ['bank_transfer', 'credit_card', 'cash', 'cheque'];
        return $methods[array_rand($methods)];
    }
}
