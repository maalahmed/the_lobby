<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Invoice;
use App\Models\LeaseContract;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $leaseContracts = LeaseContract::where('status', 'active')->get();

        foreach ($leaseContracts as $contract) {
            // Create invoices for the past 3 months
            for ($i = 0; $i < 3; $i++) {
                $dueDate = now()->subMonths($i)->startOfMonth()->addDays(0);
                $isPaid = $i > 0; // Current month unpaid, past months paid
                
                Invoice::create([
                    'lease_contract_id' => $contract->id,
                    'tenant_id' => $contract->tenant_id,
                    'invoice_number' => 'INV-' . date('Y') . '-' . str_pad($contract->id * 100 + $i, 6, '0', STR_PAD_LEFT),
                    'issue_date' => $dueDate->copy()->subDays(5),
                    'due_date' => $dueDate,
                    'amount' => $contract->rent_amount / 12, // Monthly rent
                    'tax_amount' => 0,
                    'total_amount' => $contract->rent_amount / 12,
                    'status' => $isPaid ? 'paid' : 'pending',
                    'paid_date' => $isPaid ? $dueDate->copy()->addDays(2) : null,
                    'notes' => 'Monthly rent for ' . $dueDate->format('F Y'),
                ]);
            }
        }
    }
}
