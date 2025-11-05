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
        'payment_number',
        'invoice_id',
        'tenant_id',
        'property_id',
        'amount',
        'currency',
        'payment_date',
        'payment_method',
        'payment_gateway',
        'transaction_id',
        'gateway_response',
        'status',
        'verified_by',
        'verified_at',
        'bank_name',
        'bank_transaction_ref',
        'check_number',
        'check_date',
        'receipt_url',
        'notes',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'check_date' => 'date',
        'verified_at' => 'datetime',
        'amount' => 'decimal:2',
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
            if (empty($payment->payment_number)) {
                $payment->payment_number = 'PAY-' . date('Y') . '-' . str_pad(Payment::count() + 1, 6, '0', STR_PAD_LEFT);
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
}
