<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeaseRenewalOffer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'current_contract_id',
        'property_id',
        'unit_id',
        'tenant_id',
        'landlord_id',
        'offer_date',
        'offer_expiry_date',
        'proposed_start_date',
        'proposed_end_date',
        'proposed_rent_amount',
        'current_rent_amount',
        'rent_increase_percentage',
        'rent_frequency',
        'proposed_duration_months',
        'security_deposit',
        'special_terms',
        'landlord_notes',
        'status',
        'sent_at',
        'viewed_at',
        'responded_at',
        'tenant_response_notes',
        'tenant_counter_offer_amount',
        'tenant_counter_offer_terms',
        'new_contract_id',
        'completed_at',
    ];

    protected $casts = [
        'offer_date' => 'date',
        'offer_expiry_date' => 'date',
        'proposed_start_date' => 'date',
        'proposed_end_date' => 'date',
        'proposed_rent_amount' => 'decimal:2',
        'current_rent_amount' => 'decimal:2',
        'rent_increase_percentage' => 'decimal:2',
        'security_deposit' => 'decimal:2',
        'tenant_counter_offer_amount' => 'decimal:2',
        'sent_at' => 'datetime',
        'viewed_at' => 'datetime',
        'responded_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    // Relationships
    public function currentContract()
    {
        return $this->belongsTo(LeaseContract::class, 'current_contract_id');
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function unit()
    {
        return $this->belongsTo(PropertyUnit::class, 'unit_id');
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function landlord()
    {
        return $this->belongsTo(User::class, 'landlord_id');
    }

    public function newContract()
    {
        return $this->belongsTo(LeaseContract::class, 'new_contract_id');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->whereIn('status', ['sent', 'viewed', 'negotiating']);
    }

    public function scopeExpiringSoon($query, $days = 30)
    {
        return $query->where('offer_expiry_date', '<=', now()->addDays($days))
                     ->where('offer_expiry_date', '>=', now())
                     ->whereIn('status', ['sent', 'viewed']);
    }

    // Helper Methods
    public function isExpired()
    {
        return $this->offer_expiry_date < now() && !in_array($this->status, ['accepted', 'completed']);
    }

    public function canBeAccepted()
    {
        return in_array($this->status, ['sent', 'viewed', 'negotiating']) && !$this->isExpired();
    }

    public function canBeRejected()
    {
        return in_array($this->status, ['sent', 'viewed', 'negotiating']) && !$this->isExpired();
    }

    public function getDaysUntilExpiryAttribute()
    {
        return now()->diffInDays($this->offer_expiry_date, false);
    }

    public function getRentIncreaseAmountAttribute()
    {
        return $this->proposed_rent_amount - $this->current_rent_amount;
    }
}
