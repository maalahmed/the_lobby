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
        'rent_amount',
        'rent_currency',
        'rent_frequency',
        'deposit_amount',
        'deposit_held_by',
        'late_fee_percentage',
        'late_fee_grace_days',
        'payment_due_day',
        'payment_method_allowed',
        'auto_renew',
        'renewal_notice_days',
        'early_termination_allowed',
        'early_termination_penalty',
        'maintenance_responsibility',
        'utilities_included',
        'pet_allowed',
        'smoking_allowed',
        'subletting_allowed',
        'special_terms',
        'status',
        'signed_by_tenant',
        'signed_by_landlord',
        'tenant_signature_date',
        'landlord_signature_date',
        'contract_document_url',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'rent_amount' => 'decimal:2',
        'deposit_amount' => 'decimal:2',
        'late_fee_percentage' => 'decimal:2',
        'early_termination_penalty' => 'decimal:2',
        'auto_renew' => 'boolean',
        'early_termination_allowed' => 'boolean',
        'pet_allowed' => 'boolean',
        'smoking_allowed' => 'boolean',
        'subletting_allowed' => 'boolean',
        'signed_by_tenant' => 'boolean',
        'signed_by_landlord' => 'boolean',
        'tenant_signature_date' => 'datetime',
        'landlord_signature_date' => 'datetime',
        'utilities_included' => 'json',
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
     */
    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * Get the unit for the contract.
     */
    public function unit()
    {
        return $this->belongsTo(PropertyUnit::class, 'unit_id');
    }

    /**
     * Get the tenant for the contract.
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the landlord for the contract.
     */
    public function landlord()
    {
        return $this->belongsTo(User::class, 'landlord_id');
    }

    /**
     * Get the invoices for the contract.
     */
    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'contract_id');
    }
}
