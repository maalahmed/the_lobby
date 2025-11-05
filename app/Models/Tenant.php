<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tenant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'user_id',
        'tenant_code',
        'occupation',
        'employer_name',
        'monthly_income',
        'background_check_status',
        'background_check_date',
        'credit_score',
        'previous_landlord_name',
        'previous_landlord_phone',
        'reference_name_1',
        'reference_phone_1',
        'reference_name_2',
        'reference_phone_2',
        'lease_history',
        'id_document_url',
        'income_proof_url',
        'additional_documents',
        'status',
        'notes',
    ];

    protected $casts = [
        'background_check_date' => 'date',
        'monthly_income' => 'decimal:2',
        'lease_history' => 'json',
        'additional_documents' => 'json',
        'deleted_at' => 'datetime',
    ];

    /**
     * Boot method for model events
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($tenant) {
            if (empty($tenant->uuid)) {
                $tenant->uuid = \Illuminate\Support\Str::uuid();
            }
            if (empty($tenant->tenant_code)) {
                $tenant->tenant_code = 'TEN-' . strtoupper(substr(uniqid(), -8));
            }
        });
    }

    /**
     * Get the user associated with the tenant.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the lease contracts for the tenant.
     */
    public function leaseContracts()
    {
        return $this->hasMany(LeaseContract::class);
    }

    /**
     * Get the current active lease contract.
     */
    public function currentLease()
    {
        return $this->hasOne(LeaseContract::class)
            ->where('status', 'active')
            ->latest('start_date');
    }

    /**
     * Get the invoices for the tenant.
     */
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Get the payments made by the tenant.
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get the maintenance requests by the tenant.
     */
    public function maintenanceRequests()
    {
        return $this->hasMany(MaintenanceRequest::class, 'reported_by');
    }
}
