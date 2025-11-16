<?php

namespace App\Livewire\Admin\MaintenanceJobs;

use App\Models\MaintenanceJob;
use App\Models\MaintenanceRequest;
use App\Models\ServiceProvider;
use Livewire\Component;

class Edit extends Component
{
    public MaintenanceJob $job;
    
    public $maintenance_request_id;
    public $service_provider_id;
    public $work_description;
    public $scheduled_date;
    public $scheduled_time_start;
    public $scheduled_time_end;
    public $started_at;
    public $completed_at;
    public $status;
    public $quoted_amount;
    public $final_amount;
    public $quality_rating;
    public $quality_notes;
    public $payment_status;
    public $payment_due_date;
    public $paid_at;
    public $notes;

    public function mount(MaintenanceJob $job)
    {
        $this->job = $job;
        $this->maintenance_request_id = $job->maintenance_request_id;
        $this->service_provider_id = $job->service_provider_id;
        $this->work_description = $job->work_description;
        $this->scheduled_date = $job->scheduled_date?->format('Y-m-d');
        $this->scheduled_time_start = $job->scheduled_time_start?->format('H:i');
        $this->scheduled_time_end = $job->scheduled_time_end?->format('H:i');
        $this->started_at = $job->started_at?->format('Y-m-d\TH:i');
        $this->completed_at = $job->completed_at?->format('Y-m-d\TH:i');
        $this->status = $job->status;
        $this->quoted_amount = $job->quoted_amount;
        $this->final_amount = $job->final_amount;
        $this->quality_rating = $job->quality_rating;
        $this->quality_notes = $job->quality_notes;
        $this->payment_status = $job->payment_status;
        $this->payment_due_date = $job->payment_due_date?->format('Y-m-d');
        $this->paid_at = $job->paid_at?->format('Y-m-d\TH:i');
        $this->notes = $job->notes;
    }

    public function update()
    {
        $validated = $this->validate([
            'maintenance_request_id' => 'required|exists:maintenance_requests,id',
            'service_provider_id' => 'required|exists:service_providers,id',
            'work_description' => 'required|string',
            'scheduled_date' => 'required|date',
            'scheduled_time_start' => 'nullable|date_format:H:i',
            'scheduled_time_end' => 'nullable|date_format:H:i|after:scheduled_time_start',
            'started_at' => 'nullable|date',
            'completed_at' => 'nullable|date',
            'status' => 'required|in:assigned,accepted,rejected,in_progress,completed,cancelled',
            'quoted_amount' => 'nullable|numeric|min:0',
            'final_amount' => 'nullable|numeric|min:0',
            'quality_rating' => 'nullable|integer|min:1|max:5',
            'quality_notes' => 'nullable|string',
            'payment_status' => 'required|in:pending,approved,paid',
            'payment_due_date' => 'nullable|date',
            'paid_at' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        // Convert empty strings to null for nullable fields
        $nullableFields = [
            'scheduled_time_start',
            'scheduled_time_end',
            'started_at',
            'completed_at',
            'quoted_amount',
            'final_amount',
            'quality_rating',
            'quality_notes',
            'payment_due_date',
            'paid_at',
            'notes'
        ];
        
        foreach ($nullableFields as $field) {
            if (isset($validated[$field]) && $validated[$field] === '') {
                $validated[$field] = null;
            }
        }

        // Auto-set completed_at when status changes to completed
        if ($this->status === 'completed' && $this->job->status !== 'completed' && !$validated['completed_at']) {
            $validated['completed_at'] = now();
        }

        // Auto-set started_at when status changes to in_progress
        if ($this->status === 'in_progress' && $this->job->status !== 'in_progress' && !$validated['started_at']) {
            $validated['started_at'] = now();
        }

        // Auto-set paid_at when payment_status changes to paid
        if ($this->payment_status === 'paid' && $this->job->payment_status !== 'paid' && !$validated['paid_at']) {
            $validated['paid_at'] = now();
        }

        $this->job->update($validated);

        session()->flash('message', 'Maintenance job updated successfully.');
        return redirect()->route('admin.maintenance-jobs.show', $this->job);
    }

    public function render()
    {
        $maintenanceRequests = MaintenanceRequest::with('property', 'unit')
            ->orderBy('request_number', 'desc')
            ->get();
        
        $serviceProviders = ServiceProvider::where('status', 'active')
            ->orderBy('company_name')
            ->get();

        return view('livewire.admin.maintenance-jobs.edit', [
            'maintenanceRequests' => $maintenanceRequests,
            'serviceProviders' => $serviceProviders,
        ]);
    }
}
