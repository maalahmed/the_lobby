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
        Schema::create('lease_contracts', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('contract_number', 50)->unique();
            
            // Parties
            $table->foreignId('property_id')->constrained()->onDelete('restrict');
            $table->foreignId('unit_id')->constrained('property_units')->onDelete('restrict');
            $table->foreignId('tenant_id')->constrained()->onDelete('restrict');
            $table->foreignId('landlord_id')->constrained('users')->onDelete('restrict');
            
            // Contract Dates
            $table->date('start_date');
            $table->date('end_date');
            $table->date('signed_date')->nullable();
            $table->date('move_in_date')->nullable();
            $table->date('move_out_date')->nullable();
            
            // Financial Terms
            $table->decimal('rent_amount', 10, 2);
            $table->decimal('security_deposit', 10, 2);
            $table->decimal('broker_commission', 10, 2)->default(0);
            $table->enum('rent_frequency', ['monthly', 'quarterly', 'semi_annual', 'annual'])->default('monthly');
            
            // Payment Terms
            $table->tinyInteger('payment_due_day')->unsigned()->default(1);
            $table->decimal('late_fee_amount', 8, 2)->default(0);
            $table->tinyInteger('late_fee_grace_days')->unsigned()->default(5);
            
            // Contract Terms
            $table->text('terms_conditions')->nullable();
            $table->text('special_clauses')->nullable();
            $table->json('renewal_terms')->nullable();
            $table->json('utilities_included')->nullable();
            $table->json('maintenance_responsibilities')->nullable();
            
            // Status
            $table->enum('status', ['draft', 'pending_signature', 'active', 'expired', 'terminated', 'renewed'])->default('draft');
            $table->string('termination_reason')->nullable();
            $table->date('termination_date')->nullable();
            
            // Digital Signatures
            $table->json('tenant_signature')->nullable();
            $table->json('landlord_signature')->nullable();
            
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('property_id');
            $table->index('unit_id');
            $table->index('tenant_id');
            $table->index('landlord_id');
            $table->index('status');
            $table->index(['start_date', 'end_date'], 'idx_contracts_dates');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lease_contracts');
    }
};
