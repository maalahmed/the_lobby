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
        Schema::create('property_units', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->string('unit_number', 20);
            $table->tinyInteger('floor')->nullable();
            $table->enum('type', ['studio', '1br', '2br', '3br', '4br', '5br+', 'penthouse', 'office', 'retail', 'warehouse']);
            
            // Specifications
            $table->decimal('area', 8, 2); // sq meters
            $table->tinyInteger('bedrooms')->unsigned()->default(0);
            $table->tinyInteger('bathrooms')->unsigned()->default(1);
            $table->tinyInteger('balconies')->unsigned()->default(0);
            $table->tinyInteger('parking_spaces')->unsigned()->default(0);
            
            // Rental
            $table->decimal('rent_amount', 10, 2);
            $table->decimal('security_deposit', 10, 2)->nullable();
            $table->enum('rent_frequency', ['monthly', 'quarterly', 'semi_annual', 'annual'])->default('monthly');
            
            // Details
            $table->enum('furnished', ['unfurnished', 'semi_furnished', 'fully_furnished'])->default('unfurnished');
            $table->json('amenities')->nullable();
            $table->json('features')->nullable();
            
            // Availability
            $table->enum('status', ['available', 'occupied', 'maintenance', 'reserved'])->default('available');
            $table->date('available_from')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->unique(['property_id', 'unit_number'], 'unique_property_unit');
            $table->index('property_id');
            $table->index('status');
            $table->index('type');
            $table->index('rent_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_units');
    }
};
