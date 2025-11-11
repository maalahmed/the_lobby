<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'payment_reference',
        'invoice_id',
        'tenant_id',
        'property_id',
        'amount',
        'currency',
        'payment_method',
        'gateway_name',
        'gateway_transaction_id',
        'gateway_response',
        'status',
        'verification_status',
        'payment_date',
        'processed_at',
        'verified_at',
        'bank_name',
        'check_number',
        'bank_reference',
        'notes',
        'receipt_url',
        'refunded_amount',
        'refunded_at',
        'refund_reason',
        'created_by',
        'verified_by',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'processed_at' => 'datetime',
        'verified_at' => 'datetime',
        'refunded_at' => 'datetime',
        'amount' => 'decimal:2',
        'refunded_amount' => 'decimal:2',
        'gateway_response' => 'json',
        'deleted_at' => 'datetime',
    ];

    /**
     * Boot method for model events
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($payment) {
            if (empty($payment->uuid)) {
                $payment->uuid = \Illuminate\Support\Str::uuid();
            }
            if (empty($payment->payment_reference)) {
                $payment->payment_reference = 'PAY-' . date('Y') . '-' . str_pad(Payment::count() + 1, 6, '0', STR_PAD_LEFT);
            }
        });
    }

    /**
     * Get the invoice associated with the payment.
     */
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * Get the tenant who made the payment.
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the property associated with the payment.
     */
    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * Get the user who verified the payment.
     */
    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Get the user who created the payment.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Check if payment is refunded
     */
    public function isRefunded()
    {
        return $this->status === 'refunded' || $this->refunded_amount > 0;
    }

    /**
     * Get remaining refundable amount
     */
    public function getRefundableAmountAttribute()
    {
        return $this->amount - $this->refunded_amount;
    }
}
