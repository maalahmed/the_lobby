<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'description',
        'icon',
        'color',
        'display_order',
        'is_active',
        'requires_certification',
        'requires_insurance',
        'requirements',
        'metadata',
    ];

    protected $casts = [
        'parent_id' => 'integer',
        'display_order' => 'integer',
        'is_active' => 'boolean',
        'requires_certification' => 'boolean',
        'requires_insurance' => 'boolean',
        'requirements' => 'json',
        'metadata' => 'json',
        'deleted_at' => 'datetime',
    ];

    /**
     * Boot method for model events
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = \Illuminate\Support\Str::slug($category->name);
            }
        });
    }

    /**
     * Get the parent category
     */
    public function parent()
    {
        return $this->belongsTo(ServiceCategory::class, 'parent_id');
    }

    /**
     * Get child categories
     */
    public function children()
    {
        return $this->hasMany(ServiceCategory::class, 'parent_id')
            ->orderBy('display_order');
    }

    /**
     * Get all active child categories
     */
    public function activeChildren()
    {
        return $this->children()->where('is_active', true);
    }

    /**
     * Get all service providers for this category
     */
    public function serviceProviders()
    {
        return $this->belongsToMany(
            ServiceProvider::class,
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
     * Get certified service providers only
     */
    public function certifiedProviders()
    {
        return $this->serviceProviders()
            ->wherePivot('is_certified', true);
    }

    /**
     * Get property providers that have this category active
     */
    public function propertyProviders()
    {
        return $this->belongsToMany(
            PropertyProvider::class,
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
     * Get all maintenance requests for this category
     */
    public function maintenanceRequests()
    {
        return $this->hasMany(MaintenanceRequest::class);
    }

    /**
     * Scope: Active categories only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Root categories only (no parent)
     */
    public function scopeRoots($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Scope: Order by display order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order');
    }

    /**
     * Scope: Categories requiring certification
     */
    public function scopeRequiresCertification($query)
    {
        return $query->where('requires_certification', true);
    }

    /**
     * Scope: Categories requiring insurance
     */
    public function scopeRequiresInsurance($query)
    {
        return $query->where('requires_insurance', true);
    }

    /**
     * Check if this is a root category
     */
    public function isRoot(): bool
    {
        return $this->parent_id === null;
    }

    /**
     * Check if this category has children
     */
    public function hasChildren(): bool
    {
        return $this->children()->exists();
    }

    /**
     * Get full category path (Parent > Child)
     */
    public function getFullPath(): string
    {
        if ($this->isRoot()) {
            return $this->name;
        }

        return $this->parent->name . ' > ' . $this->name;
    }

    /**
     * Get all ancestor categories
     */
    public function ancestors()
    {
        $ancestors = collect();
        $category = $this;

        while ($category->parent) {
            $ancestors->push($category->parent);
            $category = $category->parent;
        }

        return $ancestors->reverse();
    }

    /**
     * Get all descendant categories
     */
    public function descendants()
    {
        $descendants = collect();

        foreach ($this->children as $child) {
            $descendants->push($child);
            $descendants = $descendants->merge($child->descendants());
        }

        return $descendants;
    }
}
