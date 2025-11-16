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
        Schema::create('lease_renewal_offers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('current_contract_id')->constrained('lease_contracts')->onDelete('cascade');
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->foreignId('unit_id')->constrained('property_units')->onDelete('cascade');
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('landlord_id')->constrained('users')->onDelete('cascade');

            // Offer Details
            $table->date('offer_date');
            $table->date('offer_expiry_date');
            $table->date('proposed_start_date');
            $table->date('proposed_end_date');
            $table->decimal('proposed_rent_amount', 10, 2);
            $table->decimal('current_rent_amount', 10, 2);
            $table->decimal('rent_increase_percentage', 5, 2)->nullable();
            $table->string('rent_frequency')->default('annual'); // monthly, quarterly, annual

            // Terms
            $table->integer('proposed_duration_months');
            $table->decimal('security_deposit', 10, 2)->nullable();
            $table->text('special_terms')->nullable();
            $table->text('landlord_notes')->nullable();

            // Status & Response
            $table->enum('status', ['draft', 'sent', 'viewed', 'accepted', 'rejected', 'negotiating', 'expired', 'withdrawn'])->default('draft');
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('viewed_at')->nullable();
            $table->timestamp('responded_at')->nullable();
            $table->text('tenant_response_notes')->nullable();

            // Negotiation
            $table->decimal('tenant_counter_offer_amount', 10, 2)->nullable();
            $table->text('tenant_counter_offer_terms')->nullable();

            // Completion
            $table->foreignId('new_contract_id')->nullable()->constrained('lease_contracts')->onDelete('set null');
            $table->timestamp('completed_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('status');
            $table->index('offer_expiry_date');
            $table->index(['tenant_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lease_renewal_offers');
    }
};
