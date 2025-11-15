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
                'property_id' => $invoice->property_id,
                'amount' => $invoice->total_amount,
                'currency' => 'AED',
                'payment_date' => $invoice->paid_at ? $invoice->paid_at->format('Y-m-d') : now()->format('Y-m-d'),
                'payment_method' => $this->getRandomPaymentMethod(),
                'gateway_transaction_id' => 'TXN-' . strtoupper(uniqid()),
                'status' => 'completed',
                'verification_status' => 'verified',
                'processed_at' => $invoice->paid_at,
                'notes' => 'Payment for ' . $invoice->invoice_number,
            ]);
        }
    }

    private function getRandomPaymentMethod(): string
    {
        $methods = ['bank_transfer', 'card', 'cash', 'check'];
        return $methods[array_rand($methods)];
    }
}
