<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Property extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'uuid',
        'owner_id',
        'property_code',
        'name',
        'name_ar',
        'description',
        'description_ar',
        'property_type',
        'address_line_1',
        'address_line_2',
        'city',
        'state',
        'postal_code',
        'country',
        'latitude',
        'longitude',
        'year_built',
        'total_units',
        'total_floors',
        'parking_spaces',
        'total_area',
        'land_area',
        'purchase_price',
        'purchase_date',
        'current_value',
        'amenities_list',
        'managed_by',
        'management_start_date',
        'property_tax_annual',
        'insurance_annual',
        'insurance_policy_number',
        'insurance_expiry_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'management_start_date' => 'date',
        'insurance_expiry_date' => 'date',
        'amenities_list' => 'json',
        'purchase_price' => 'decimal:2',
        'current_value' => 'decimal:2',
        'property_tax_annual' => 'decimal:2',
        'insurance_annual' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'deleted_at' => 'datetime',
    ];

    /**
     * Boot method for model events
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($property) {
            if (empty($property->uuid)) {
                $property->uuid = \Illuminate\Support\Str::uuid();
            }
            if (empty($property->property_code)) {
                $property->property_code = 'PROP-' . strtoupper(substr(uniqid(), -8));
            }
        });
    }

    /**
     * Get the owner of the property.
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Get the manager of the property.
     */
    public function manager()
    {
        return $this->belongsTo(User::class, 'managed_by');
    }

    /**
     * Get the units for the property.
     */
    public function units()
    {
        return $this->hasMany(PropertyUnit::class);
    }

    /**
     * Get the amenities for the property.
     */
    public function amenities()
    {
        return $this->hasMany(PropertyAmenity::class);
    }

    /**
     * Get the lease contracts for the property.
     */
    public function leaseContracts()
    {
        return $this->hasMany(LeaseContract::class);
    }

    /**
     * Get the maintenance requests for the property.
     */
    public function maintenanceRequests()
    {
        return $this->hasMany(MaintenanceRequest::class);
    }

    /**
     * Get the invoices for the property.
     */
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Register media collections for property images.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
            ->useFallbackUrl('/images/property-placeholder.jpg')
            ->useFallbackPath(public_path('/images/property-placeholder.jpg'));

        $this->addMediaCollection('documents');
    }
}
