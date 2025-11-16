<?php

namespace App\Livewire\Admin\MaintenanceRequests;

use App\Models\MaintenanceRequest;
use App\Models\Property;
use App\Models\PropertyUnit;
use App\Models\Tenant;
use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;

class Edit extends Component
{
    use WithFileUploads;

    public MaintenanceRequest $request;
    
    public $property_id;
    public $unit_id;
    public $tenant_id;
    public $title;
    public $description;
    public $category;
    public $priority;
    public $assigned_to;
    public $status;
    
    // Scheduling
    public $preferred_date;
    public $preferred_time_start;
    public $preferred_time_end;
    public $scheduled_date;
    public $scheduled_time_start;
    public $scheduled_time_end;
    
    // Access
    public $access_instructions;
    public $tenant_present_required;
    public $keys_required;
    
    // Cost
    public $estimated_cost;
    public $approved_cost;
    public $final_cost;
    public $cost_approval_required;
    public $cost_approved_by;
    
    // Completion
    public $completed_at;
    public $completion_notes;
    public $tenant_satisfaction_rating;
    public $tenant_feedback;
    
    // Recurring
    public $is_recurring;
    public $recurring_frequency;
    public $next_due_date;
    
    // Units for selected property
    public $units = [];

    public function mount(MaintenanceRequest $request)
    {
        $this->request = $request;
        
        $this->property_id = $request->property_id;
        $this->unit_id = $request->unit_id;
        $this->tenant_id = $request->tenant_id;
        $this->title = $request->title;
        $this->description = $request->description;
        $this->category = $request->category;
        $this->priority = $request->priority;
        $this->assigned_to = $request->assigned_to;
        $this->status = $request->status;
        
        $this->preferred_date = $request->preferred_date?->format('Y-m-d');
        $this->preferred_time_start = $request->preferred_time_start;
        $this->preferred_time_end = $request->preferred_time_end;
        $this->scheduled_date = $request->scheduled_date?->format('Y-m-d');
        $this->scheduled_time_start = $request->scheduled_time_start;
        $this->scheduled_time_end = $request->scheduled_time_end;
        
        $this->access_instructions = $request->access_instructions;
        $this->tenant_present_required = $request->tenant_present_required;
        $this->keys_required = $request->keys_required;
        
        $this->estimated_cost = $request->estimated_cost;
        $this->approved_cost = $request->approved_cost;
        $this->final_cost = $request->final_cost;
        $this->cost_approval_required = $request->cost_approval_required;
        
        $this->completion_notes = $request->completion_notes;
        $this->tenant_satisfaction_rating = $request->tenant_satisfaction_rating;
        $this->tenant_feedback = $request->tenant_feedback;
        
        $this->is_recurring = $request->is_recurring;
        $this->recurring_frequency = $request->recurring_frequency;
        $this->next_due_date = $request->next_due_date?->format('Y-m-d');
        
        // Load units for the property
        if ($this->property_id) {
            $this->units = PropertyUnit::where('property_id', $this->property_id)
                ->orderBy('unit_number')
                ->get();
        }
    }

    public function updatedPropertyId($value)
    {
        $this->units = [];
        $this->unit_id = '';
        
        if ($value) {
            $this->units = PropertyUnit::where('property_id', $value)
                ->orderBy('unit_number')
                ->get();
        }
    }

    public function update()
    {
        $validated = $this->validate([
            'property_id' => 'required|exists:properties,id',
            'unit_id' => 'nullable|exists:property_units,id',
            'tenant_id' => 'nullable|exists:tenants,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|in:plumbing,electrical,hvac,appliance,structural,pest_control,cleaning,landscaping,security,other',
            'priority' => 'required|in:low,normal,high,urgent',
            'assigned_to' => 'nullable|exists:users,id',
            'status' => 'required|in:pending,approved,assigned,in_progress,on_hold,completed,cancelled',
            'preferred_date' => 'nullable|date',
            'preferred_time_start' => 'nullable|date_format:H:i',
            'preferred_time_end' => 'nullable|date_format:H:i|after:preferred_time_start',
            'scheduled_date' => 'nullable|date',
            'scheduled_time_start' => 'nullable|date_format:H:i',
            'scheduled_time_end' => 'nullable|date_format:H:i|after:scheduled_time_start',
            'access_instructions' => 'nullable|string',
            'tenant_present_required' => 'boolean',
            'keys_required' => 'boolean',
            'estimated_cost' => 'nullable|numeric|min:0',
            'approved_cost' => 'nullable|numeric|min:0',
            'final_cost' => 'nullable|numeric|min:0',
            'cost_approval_required' => 'boolean',
            'completion_notes' => 'nullable|string',
            'tenant_satisfaction_rating' => 'nullable|integer|min:1|max:5',
            'tenant_feedback' => 'nullable|string',
            'is_recurring' => 'boolean',
            'recurring_frequency' => 'nullable|in:weekly,monthly,quarterly,semi_annual,annual',
            'next_due_date' => 'nullable|date',
        ]);

        // Convert empty strings to null for nullable fields
        $nullableFields = [
            'unit_id',
            'tenant_id', 
            'assigned_to',
            'preferred_date',
            'preferred_time_start', 
            'preferred_time_end', 
            'scheduled_date',
            'scheduled_time_start', 
            'scheduled_time_end',
            'access_instructions',
            'estimated_cost',
            'approved_cost',
            'final_cost',
            'completion_notes',
            'tenant_satisfaction_rating',
            'tenant_feedback',
            'recurring_frequency',
            'next_due_date'
        ];
        
        foreach ($nullableFields as $field) {
            if (isset($validated[$field]) && $validated[$field] === '') {
                $validated[$field] = null;
            }
        }

        // Handle status change to completed
        if ($this->status === 'completed' && $this->request->status !== 'completed') {
            $validated['completed_at'] = now();
        }

        // Handle cost approval
        if ($this->approved_cost && !$this->request->cost_approved_at) {
            $validated['cost_approved_by'] = Auth::id();
            $validated['cost_approved_at'] = now();
        }

        $this->request->update($validated);

        session()->flash('message', 'Maintenance request updated successfully.');
        return redirect()->route('admin.maintenance-requests.show', $this->request);
    }

    public function render()
    {
        $properties = Property::select('id', 'name')->orderBy('name')->get();
        $tenants = Tenant::with('user')->get();
        $users = User::select('id', 'name')->orderBy('name')->get();

        return view('livewire.admin.maintenance-requests.edit', [
            'properties' => $properties,
            'tenants' => $tenants,
            'users' => $users,
        ])->layout('layouts.admin');
    }
}
