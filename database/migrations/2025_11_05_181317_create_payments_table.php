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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('payment_reference', 100)->unique();
            
            // Related Entities
            $table->foreignId('invoice_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('tenant_id')->constrained()->onDelete('restrict');
            $table->foreignId('property_id')->constrained()->onDelete('restrict');
            
            // Payment Details
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('SAR');
            $table->enum('payment_method', ['cash', 'bank_transfer', 'check', 'card', 'online', 'mobile_payment']);
            
            // Gateway
            $table->string('gateway_name', 50)->nullable();
            $table->string('gateway_transaction_id')->nullable();
            $table->json('gateway_response')->nullable();
            
            // Status
            $table->enum('status', ['pending', 'processing', 'completed', 'failed', 'cancelled', 'refunded'])->default('pending');
            $table->enum('verification_status', ['not_required', 'pending', 'verified', 'rejected'])->default('not_required');
            
            // Dates
            $table->date('payment_date');
            $table->timestamp('processed_at')->nullable();
            $table->timestamp('verified_at')->nullable();
            
            // Bank/Check
            $table->string('bank_name')->nullable();
            $table->string('check_number', 50)->nullable();
            $table->string('bank_reference')->nullable();
            
            // Additional
            $table->text('notes')->nullable();
            $table->string('receipt_url', 500)->nullable();
            
            // Refund
            $table->decimal('refunded_amount', 10, 2)->default(0);
            $table->timestamp('refunded_at')->nullable();
            $table->text('refund_reason')->nullable();
            
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('invoice_id');
            $table->index('tenant_id');
            $table->index('property_id');
            $table->index('status');
            $table->index('payment_date');
            $table->index('payment_method');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
