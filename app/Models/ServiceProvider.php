<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceProvider extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'user_id',
        'provider_code',
        'company_name',
        'company_name_ar',
        'trade_license_number',
        'tax_registration_number',
        'contact_person',
        'contact_phone',
        'contact_email',
        'address_line_1',
        'address_line_2',
        'city',
        'state',
        'postal_code',
        'country',
        'services_offered',
        'service_areas',
        'established_year',
        'insurance_provider',
        'insurance_policy_number',
        'insurance_expiry_date',
        'certifications',
        'hourly_rate',
        'minimum_charge',
        'payment_terms',
        'average_rating',
        'total_jobs_completed',
        'status',
        'notes',
    ];

    protected $casts = [
        'insurance_expiry_date' => 'date',
        'hourly_rate' => 'decimal:2',
        'minimum_charge' => 'decimal:2',
        'average_rating' => 'decimal:2',
        'services_offered' => 'json',
        'service_areas' => 'json',
        'certifications' => 'json',
        'deleted_at' => 'datetime',
    ];

    /**
     * Boot method for model events
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($provider) {
            if (empty($provider->uuid)) {
                $provider->uuid = \Illuminate\Support\Str::uuid();
            }
            if (empty($provider->provider_code)) {
                $provider->provider_code = 'SP-' . strtoupper(substr(uniqid(), -8));
            }
        });
    }

    /**
     * Get the user associated with the service provider.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get service categories for this provider
     */
    public function serviceCategories()
    {
        return $this->belongsToMany(
            ServiceCategory::class,
            'service_provider_categories'
        )
        ->withPivot([
            'experience_years',
            'is_primary',
            'is_certified',
            'certifications',
            'licenses',
            'hourly_rate',
            'minimum_charge',
            'pricing_notes',
            'service_hours',
            'emergency_available',
        ])
        ->withTimestamps();
    }

    /**
     * Get certified categories only
     */
    public function certifiedCategories()
    {
        return $this->serviceCategories()
            ->wherePivot('is_certified', true);
    }

    /**
     * Get primary categories
     */
    public function primaryCategories()
    {
        return $this->serviceCategories()
            ->wherePivot('is_primary', true);
    }

    /**
     * Get property providers this service provider works with
     */
    public function propertyProviders()
    {
        return $this->belongsToMany(
            PropertyProvider::class,
            'property_provider_service_providers'
        )
        ->withPivot([
            'status',
            'is_preferred',
            'priority',
            'rate_multiplier',
            'payment_terms',
            'custom_rates',
            'jobs_assigned',
            'jobs_completed',
            'jobs_cancelled',
            'avg_rating',
            'avg_completion_time',
            'restrictions',
            'notes',
            'admin_notes',
            'relationship_started_at',
            'relationship_ended_at',
        ])
        ->withTimestamps();
    }

    /**
     * Get active property providers
     */
    public function activePropertyProviders()
    {
        return $this->propertyProviders()
            ->wherePivot('status', 'active');
    }

    /**
     * Get the maintenance jobs for the service provider.
     */
    public function maintenanceJobs()
    {
        return $this->hasMany(MaintenanceJob::class);
    }

    /**
     * Get the completed jobs for the service provider.
     */
    public function completedJobs()
    {
        return $this->hasMany(MaintenanceJob::class)
            ->where('status', 'completed');
    }

    /**
     * Scope: Filter by service category
     */
    public function scopeByCategory($query, $categoryId)
    {
        return $query->whereHas('serviceCategories', function ($q) use ($categoryId) {
            $q->where('service_category_id', $categoryId);
        });
    }

    /**
     * Scope: Certified providers only
     */
    public function scopeCertified($query, $categoryId = null)
    {
        return $query->whereHas('serviceCategories', function ($q) use ($categoryId) {
            $q->where('is_certified', true);
            if ($categoryId) {
                $q->where('service_category_id', $categoryId);
            }
        });
    }

    /**
     * Scope: Filter by property provider
     */
    public function scopeByPropertyProvider($query, $propertyProviderId)
    {
        return $query->whereHas('propertyProviders', function ($q) use ($propertyProviderId) {
            $q->where('property_provider_id', $propertyProviderId)
              ->where('status', 'active');
        });
    }

    /**
     * Check if provider has a specific category
     */
    public function hasCategory($categoryId): bool
    {
        return $this->serviceCategories()
            ->where('service_category_id', $categoryId)
            ->exists();
    }

    /**
     * Check if provider is certified for a category
     */
    public function isCertifiedFor($categoryId): bool
    {
        return $this->serviceCategories()
            ->where('service_category_id', $categoryId)
            ->wherePivot('is_certified', true)
            ->exists();
    }
}
