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
}
