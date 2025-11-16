<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeaseContract extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'contract_number',
        'property_id',
        'unit_id',
        'tenant_id',
        'landlord_id',
        'start_date',
        'end_date',
        'signed_date',
        'move_in_date',
        'move_out_date',
        'rent_amount',
        'security_deposit',
        'broker_commission',
        'rent_frequency',
        'payment_due_day',
        'late_fee_amount',
        'late_fee_grace_days',
        'terms_conditions',
        'special_clauses',
        'renewal_terms',
        'utilities_included',
        'maintenance_responsibilities',
        'status',
        'termination_reason',
        'termination_date',
        'tenant_signature',
        'landlord_signature',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'signed_date' => 'date',
        'move_in_date' => 'date',
        'move_out_date' => 'date',
        'termination_date' => 'date',
        'rent_amount' => 'decimal:2',
        'security_deposit' => 'decimal:2',
        'broker_commission' => 'decimal:2',
        'late_fee_amount' => 'decimal:2',
        'renewal_terms' => 'json',
        'utilities_included' => 'json',
        'maintenance_responsibilities' => 'json',
        'tenant_signature' => 'json',
        'landlord_signature' => 'json',
        'deleted_at' => 'datetime',
    ];

    /**
     * Boot method for model events
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($contract) {
            if (empty($contract->uuid)) {
                $contract->uuid = \Illuminate\Support\Str::uuid();
            }
            if (empty($contract->contract_number)) {
                $contract->contract_number = 'LC-' . date('Y') . '-' . strtoupper(substr(uniqid(), -8));
            }
        });
    }

    /**
     * Get the property for the contract.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Property, LeaseContract>
     */
    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * Get the unit for the contract.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<PropertyUnit, LeaseContract>
     */
    public function unit()
    {
        return $this->belongsTo(PropertyUnit::class, 'unit_id');
    }

    /**
     * Get the tenant for the contract.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Tenant, LeaseContract>
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the landlord for the contract.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<User, LeaseContract>
     */
    public function landlord()
    {
        return $this->belongsTo(User::class, 'landlord_id');
    }

    /**
     * Get the user who created the contract.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<User, LeaseContract>
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the invoices for the contract.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Invoice>
     */
    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'contract_id');
    }

    /**
     * Get the renewal offers for the contract.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<LeaseRenewalOffer>
     */
    public function renewalOffers()
    {
        return $this->hasMany(LeaseRenewalOffer::class, 'contract_id');
    }
}
