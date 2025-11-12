<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaintenanceRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'request_number',
        'property_id',
        'unit_id',
        'title',
        'description',
        'category',
        'priority',
        'requested_by',
        'tenant_id',
        'assigned_to',
        'status',
        'preferred_date',
        'preferred_time_start',
        'preferred_time_end',
        'scheduled_date',
        'scheduled_time_start',
        'scheduled_time_end',
        'access_instructions',
        'tenant_present_required',
        'keys_required',
        'estimated_cost',
        'approved_cost',
        'final_cost',
        'cost_approval_required',
        'cost_approved_by',
        'cost_approved_at',
        'completed_at',
        'completion_notes',
        'tenant_satisfaction_rating',
        'tenant_feedback',
        'initial_photos',
        'completion_photos',
        'is_recurring',
        'recurring_frequency',
        'next_due_date',
    ];

    protected $casts = [
        'preferred_date' => 'date',
        'scheduled_date' => 'date',
        'completed_at' => 'datetime',
        'cost_approved_at' => 'datetime',
        'next_due_date' => 'date',
        'estimated_cost' => 'decimal:2',
        'approved_cost' => 'decimal:2',
        'final_cost' => 'decimal:2',
        'initial_photos' => 'json',
        'completion_photos' => 'json',
        'is_recurring' => 'boolean',
        'tenant_present_required' => 'boolean',
        'keys_required' => 'boolean',
        'cost_approval_required' => 'boolean',
        'deleted_at' => 'datetime',
    ];

    /**
     * Boot method for model events
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($request) {
            if (empty($request->uuid)) {
                $request->uuid = \Illuminate\Support\Str::uuid();
            }
            if (empty($request->request_number)) {
                $request->request_number = 'MR-' . date('Y') . '-' . str_pad(MaintenanceRequest::count() + 1, 6, '0', STR_PAD_LEFT);
            }
        });
    }

    /**
     * Get the property for the maintenance request.
     */
    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * Get the unit for the maintenance request.
     */
    public function unit()
    {
        return $this->belongsTo(PropertyUnit::class, 'unit_id');
    }

    /**
     * Get the user who requested the maintenance.
     */
    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    /**
     * Get the tenant associated with the request.
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the user assigned to the request.
     */
    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Get the user who approved the cost.
     */
    public function costApprover()
    {
        return $this->belongsTo(User::class, 'cost_approved_by');
    }

    /**
     * Get the maintenance jobs for the request.
     */
    public function jobs()
    {
        return $this->hasMany(MaintenanceJob::class);
    }

    /**
     * Check if request is overdue
     */
    public function isOverdue()
    {
        return $this->scheduled_date && 
               $this->scheduled_date->isPast() && 
               !in_array($this->status, ['completed', 'cancelled']);
    }

    /**
     * Check if cost approval is pending
     */
    public function needsCostApproval()
    {
        return $this->cost_approval_required && !$this->cost_approved_at;
    }
}
