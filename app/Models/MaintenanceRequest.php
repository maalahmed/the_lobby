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
        'reported_by',
        'assigned_to',
        'category',
        'priority',
        'title',
        'description',
        'location_details',
        'status',
        'scheduled_date',
        'scheduled_time',
        'completed_at',
        'tenant_available_from',
        'tenant_available_to',
        'access_instructions',
        'estimated_cost',
        'actual_cost',
        'cost_covered_by',
        'completion_notes',
        'completion_photos',
        'tenant_rating',
        'tenant_feedback',
        'is_recurring',
        'recurring_frequency',
        'next_occurrence_date',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'scheduled_time' => 'datetime',
        'completed_at' => 'datetime',
        'tenant_available_from' => 'datetime',
        'tenant_available_to' => 'datetime',
        'next_occurrence_date' => 'date',
        'estimated_cost' => 'decimal:2',
        'actual_cost' => 'decimal:2',
        'completion_photos' => 'json',
        'is_recurring' => 'boolean',
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
     * Get the user who reported the request.
     */
    public function reporter()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    /**
     * Get the user assigned to the request.
     */
    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Get the maintenance jobs for the request.
     */
    public function jobs()
    {
        return $this->hasMany(MaintenanceJob::class);
    }
}
