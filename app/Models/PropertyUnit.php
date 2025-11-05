<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class PropertyUnit extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'uuid',
        'property_id',
        'unit_number',
        'unit_type',
        'floor_number',
        'bedrooms',
        'bathrooms',
        'area_sqft',
        'area_sqm',
        'rent_amount',
        'rent_currency',
        'rent_frequency',
        'deposit_amount',
        'furnished_status',
        'available_from',
        'status',
        'description',
        'description_ar',
        'features',
    ];

    protected $casts = [
        'available_from' => 'date',
        'rent_amount' => 'decimal:2',
        'deposit_amount' => 'decimal:2',
        'area_sqft' => 'decimal:2',
        'area_sqm' => 'decimal:2',
        'features' => 'json',
        'deleted_at' => 'datetime',
    ];

    /**
     * Boot method for model events
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($unit) {
            if (empty($unit->uuid)) {
                $unit->uuid = \Illuminate\Support\Str::uuid();
            }
        });
    }

    /**
     * Get the property that owns the unit.
     */
    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * Get the lease contracts for the unit.
     */
    public function leaseContracts()
    {
        return $this->hasMany(LeaseContract::class, 'unit_id');
    }

    /**
     * Get the current active lease contract.
     */
    public function currentLease()
    {
        return $this->hasOne(LeaseContract::class, 'unit_id')
            ->where('status', 'active')
            ->latest('start_date');
    }

    /**
     * Get the maintenance requests for the unit.
     */
    public function maintenanceRequests()
    {
        return $this->hasMany(MaintenanceRequest::class, 'unit_id');
    }

    /**
     * Get the invoices for the unit.
     */
    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'unit_id');
    }

    /**
     * Register media collections for unit images.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
            ->useFallbackUrl('/images/unit-placeholder.jpg')
            ->useFallbackPath(public_path('/images/unit-placeholder.jpg'));

        $this->addMediaCollection('documents');
    }
}
