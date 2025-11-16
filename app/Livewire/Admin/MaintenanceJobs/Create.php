<?php

namespace App\Livewire\Admin\MaintenanceJobs;

use App\Models\MaintenanceJob;
use App\Models\MaintenanceRequest;
use App\Models\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Create extends Component
{
    public $maintenance_request_id;
    public $service_provider_id;
    public $work_description;
    public $scheduled_date;
    public $scheduled_time_start;
    public $scheduled_time_end;
    public $quoted_amount;
    public $payment_due_date;
    public $notes;
    public $status = 'assigned';
    public $payment_status = 'pending';

    public function mount()
    {
        // Pre-fill from query string if maintenance_request_id is passed
        if (request()->has('maintenance_request_id')) {
            $this->maintenance_request_id = request('maintenance_request_id');
        }
    }

    public function save()
    {
        $validated = $this->validate([
            'maintenance_request_id' => 'required|exists:maintenance_requests,id',
            'service_provider_id' => 'required|exists:service_providers,id',
            'work_description' => 'required|string',
            'scheduled_date' => 'required|date',
            'scheduled_time_start' => 'nullable|date_format:H:i',
            'scheduled_time_end' => 'nullable|date_format:H:i|after:scheduled_time_start',
            'quoted_amount' => 'nullable|numeric|min:0',
            'payment_due_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'status' => 'required|in:assigned,accepted,rejected,in_progress,completed,cancelled',
            'payment_status' => 'required|in:pending,approved,paid',
        ]);

        $validated['assigned_by'] = Auth::id();

        // Convert empty strings to null for nullable fields
        $nullableFields = [
            'scheduled_time_start',
            'scheduled_time_end',
            'quoted_amount',
            'payment_due_date',
            'notes'
        ];
        
        foreach ($nullableFields as $field) {
            if (isset($validated[$field]) && $validated[$field] === '') {
                $validated[$field] = null;
            }
        }

        $job = MaintenanceJob::create($validated);

        session()->flash('message', 'Maintenance job created successfully.');
        return redirect()->route('admin.maintenance-jobs.show', $job);
    }

    public function render()
    {
        $maintenanceRequests = MaintenanceRequest::with('property', 'unit')
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->orderBy('request_number', 'desc')
            ->get();
        
        $serviceProviders = ServiceProvider::where('status', 'active')
            ->orderBy('company_name')
            ->get();

        return view('livewire.admin.maintenance-jobs.create', [
            'maintenanceRequests' => $maintenanceRequests,
            'serviceProviders' => $serviceProviders,
        ])->layout('layouts.admin', ['title' => 'Create Maintenance Job']);
    }
}
