<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaintenanceJob extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'job_number',
        'maintenance_request_id',
        'service_provider_id',
        'assigned_by',
        'work_description',
        'work_items',
        'scheduled_date',
        'scheduled_time_start',
        'scheduled_time_end',
        'started_at',
        'completed_at',
        'status',
        'quoted_amount',
        'final_amount',
        'cost_breakdown',
        'quality_rating',
        'quality_notes',
        'completion_photos',
        'payment_status',
        'payment_due_date',
        'paid_at',
        'notes',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'scheduled_time_start' => 'datetime',
        'scheduled_time_end' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'payment_due_date' => 'date',
        'paid_at' => 'datetime',
        'quoted_amount' => 'decimal:2',
        'final_amount' => 'decimal:2',
        'work_items' => 'json',
        'cost_breakdown' => 'json',
        'completion_photos' => 'json',
        'deleted_at' => 'datetime',
    ];

    /**
     * Boot method for model events
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($job) {
            if (empty($job->uuid)) {
                $job->uuid = \Illuminate\Support\Str::uuid();
            }
            if (empty($job->job_number)) {
                $job->job_number = 'MJ-' . date('Y') . '-' . str_pad(MaintenanceJob::count() + 1, 6, '0', STR_PAD_LEFT);
            }
        });
    }

    /**
     * Get the maintenance request for the job.
     */
    public function maintenanceRequest()
    {
        return $this->belongsTo(MaintenanceRequest::class);
    }

    /**
     * Get the service provider assigned to the job.
     */
    public function serviceProvider()
    {
        return $this->belongsTo(ServiceProvider::class);
    }

    /**
     * Get the user who assigned the job.
     */
    public function assigner()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
}
