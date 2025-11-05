<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'invoice_number',
        'contract_id',
        'tenant_id',
        'property_id',
        'unit_id',
        'type',
        'description',
        'subtotal',
        'tax_amount',
        'discount_amount',
        'total_amount',
        'invoice_date',
        'due_date',
        'service_period_start',
        'service_period_end',
        'status',
        'paid_amount',
        'paid_at',
        'line_items',
        'notes',
        'payment_terms',
        'created_by',
        'sent_at',
        'viewed_at',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'service_period_start' => 'date',
        'service_period_end' => 'date',
        'paid_at' => 'datetime',
        'sent_at' => 'datetime',
        'viewed_at' => 'datetime',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'line_items' => 'json',
        'deleted_at' => 'datetime',
    ];

    /**
     * Boot method for model events
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($invoice) {
            if (empty($invoice->uuid)) {
                $invoice->uuid = \Illuminate\Support\Str::uuid();
            }
            if (empty($invoice->invoice_number)) {
                $invoice->invoice_number = 'INV-' . date('Y') . '-' . str_pad(Invoice::count() + 1, 6, '0', STR_PAD_LEFT);
            }
        });
    }

    /**
     * Get the contract associated with the invoice.
     */
    public function contract()
    {
        return $this->belongsTo(LeaseContract::class, 'contract_id');
    }

    /**
     * Get the tenant associated with the invoice.
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the property associated with the invoice.
     */
    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * Get the unit associated with the invoice.
     */
    public function unit()
    {
        return $this->belongsTo(PropertyUnit::class, 'unit_id');
    }

    /**
     * Get the user who created the invoice.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the payments for the invoice.
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Check if invoice is overdue.
     */
    public function isOverdue()
    {
        return $this->status !== 'paid' && $this->due_date < now();
    }

    /**
     * Get the remaining balance.
     */
    public function getRemainingBalanceAttribute()
    {
        return $this->total_amount - $this->paid_amount;
    }
}
