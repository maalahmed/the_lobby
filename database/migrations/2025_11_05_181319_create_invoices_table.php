<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('invoice_number', 50)->unique();
            
            // Related Entities
            $table->foreignId('contract_id')->nullable()->constrained('lease_contracts')->onDelete('set null');
            $table->foreignId('tenant_id')->constrained()->onDelete('restrict');
            $table->foreignId('property_id')->constrained()->onDelete('restrict');
            $table->foreignId('unit_id')->nullable()->constrained('property_units')->onDelete('restrict');
            
            // Invoice Details
            $table->enum('type', ['rent', 'deposit', 'late_fee', 'utility', 'maintenance', 'service', 'other']);
            $table->text('description')->nullable();
            
            // Amounts
            $table->decimal('subtotal', 10, 2);
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2);
            
            // Dates
            $table->date('invoice_date');
            $table->date('due_date');
            $table->date('service_period_start')->nullable();
            $table->date('service_period_end')->nullable();
            
            // Status
            $table->enum('status', ['draft', 'sent', 'viewed', 'partial_paid', 'paid', 'overdue', 'cancelled', 'refunded'])->default('draft');
            
            // Payment
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->timestamp('paid_at')->nullable();
            
            // Line Items & Notes
            $table->json('line_items')->nullable();
            $table->text('notes')->nullable();
            $table->string('payment_terms')->nullable();
            
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('viewed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('contract_id');
            $table->index('tenant_id');
            $table->index('property_id');
            $table->index('status');
            $table->index('due_date');
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
