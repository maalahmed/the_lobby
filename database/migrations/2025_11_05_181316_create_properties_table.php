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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('owner_id')->constrained('users')->onDelete('restrict');
            $table->string('property_code', 50)->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('type', ['residential', 'commercial', 'mixed', 'industrial']);
            $table->string('sub_type', 100)->nullable(); // apartment, villa, office, retail
            $table->enum('status', ['active', 'inactive', 'under_construction', 'maintenance'])->default('active');
            
            // Address
            $table->string('address_line_1');
            $table->string('address_line_2')->nullable();
            $table->string('city', 100);
            $table->string('state', 100);
            $table->string('postal_code', 20)->nullable();
            $table->string('country', 100)->default('Saudi Arabia');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            
            // Property Details
            $table->year('built_year')->nullable();
            $table->decimal('total_area', 10, 2)->nullable(); // sq meters
            $table->tinyInteger('total_floors')->unsigned()->nullable();
            $table->smallInteger('total_units')->unsigned()->default(0);
            $table->smallInteger('parking_spaces')->unsigned()->default(0);
            
            // Financial
            $table->decimal('purchase_price', 15, 2)->nullable();
            $table->decimal('current_value', 15, 2)->nullable();
            $table->decimal('monthly_expenses', 10, 2)->nullable();
            
            // Additional
            $table->json('amenities')->nullable();
            $table->json('utilities')->nullable();
            $table->text('rules_regulations')->nullable();
            
            // Management
            $table->string('management_company')->nullable();
            $table->foreignId('property_manager_id')->nullable()->constrained('users')->onDelete('set null');
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('owner_id');
            $table->index('property_code');
            $table->index('type');
            $table->index('status');
            $table->index('city');
            $table->index(['latitude', 'longitude'], 'idx_properties_location');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
