<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PropertyProvider extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'company_name',
        'business_registration',
        'tax_number',
        'website',
        'contact_name',
        'contact_email',
        'contact_phone',
        'address',
        'billing_address',
        'description',
        'service_areas',
        'properties_count',
        'units_count',
        'subscription_tier',
        'subscription_expires_at',
        'billing_details',
        'settings',
        'timezone',
        'currency',
        'language',
        'status',
        'notes',
    ];

    protected $casts = [
        'address' => 'json',
        'billing_address' => 'json',
        'service_areas' => 'json',
        'billing_details' => 'json',
        'settings' => 'json',
        'subscription_expires_at' => 'date',
        'properties_count' => 'integer',
        'units_count' => 'integer',
        'deleted_at' => 'datetime',
    ];

    /**
     * Boot method for model events
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($propertyProvider) {
            if (empty($propertyProvider->uuid)) {
                $propertyProvider->uuid = \Illuminate\Support\Str::uuid();
            }
        });
    }

    /**
     * Get all properties belonging to this property provider
     */
    public function properties()
    {
        return $this->hasMany(Property::class);
    }

    /**
     * Get all service providers associated with this property provider
     */
    public function serviceProviders()
    {
        return $this->belongsToMany(
            ServiceProvider::class,
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
     * Get only active service providers
     */
    public function activeServiceProviders()
    {
        return $this->serviceProviders()
            ->wherePivot('status', 'active');
    }

    /**
     * Get preferred service providers
     */
    public function preferredServiceProviders()
    {
        return $this->serviceProviders()
            ->wherePivot('status', 'active')
            ->wherePivot('is_preferred', true)
            ->orderByPivot('priority', 'asc');
    }

    /**
     * Get active service categories for this property provider
     */
    public function activeCategories()
    {
        return $this->belongsToMany(
            ServiceCategory::class,
            'property_active_categories'
        )
        ->withPivot([
            'is_active',
            'activation_notes',
            'auto_assign',
            'requires_approval',
            'max_concurrent_jobs',
            'max_response_time_minutes',
            'max_completion_time_hours',
            'min_quote_amount',
            'max_quote_amount',
            'requires_multiple_quotes',
            'min_quotes_required',
            'notification_settings',
            'activated_at',
            'deactivated_at',
            'activated_by',
        ])
        ->wherePivot('is_active', true)
        ->withTimestamps();
    }

    /**
     * Get all service categories (active and inactive)
     */
    public function serviceCategories()
    {
        return $this->belongsToMany(
            ServiceCategory::class,
            'property_active_categories'
        )
        ->withPivot([
            'is_active',
            'activation_notes',
            'auto_assign',
            'requires_approval',
            'max_concurrent_jobs',
            'max_response_time_minutes',
            'max_completion_time_hours',
            'min_quote_amount',
            'max_quote_amount',
            'requires_multiple_quotes',
            'min_quotes_required',
            'notification_settings',
            'activated_at',
            'deactivated_at',
            'activated_by',
        ])
        ->withTimestamps();
    }

    /**
     * Get all maintenance requests for this property provider
     */
    public function maintenanceRequests()
    {
        return $this->hasManyThrough(
            MaintenanceRequest::class,
            Property::class
        );
    }

    /**
     * Scope: Active property providers only
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope: Filter by subscription tier
     */
    public function scopeByTier($query, $tier)
    {
        return $query->where('subscription_tier', $tier);
    }

    /**
     * Scope: Subscription expiring soon
     */
    public function scopeExpiringSoon($query, $days = 30)
    {
        return $query->where('subscription_expires_at', '<=', now()->addDays($days))
            ->where('subscription_expires_at', '>=', now());
    }

    /**
     * Check if subscription is active
     */
    public function hasActiveSubscription(): bool
    {
        if (!$this->subscription_expires_at) {
            return true; // No expiration set
        }

        return $this->subscription_expires_at->isFuture();
    }

    /**
     * Check if a service category is active
     */
    public function hasCategoryActive($categoryId): bool
    {
        return $this->activeCategories()
            ->where('service_category_id', $categoryId)
            ->exists();
    }

    /**
     * Check if a service provider is associated
     */
    public function hasServiceProvider($providerId): bool
    {
        return $this->activeServiceProviders()
            ->where('service_provider_id', $providerId)
            ->exists();
    }
}
