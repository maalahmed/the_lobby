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

class Create extends Component
{
    use WithFileUploads;

    public $property_id = '';
    public $unit_id = '';
    public $tenant_id = '';
    public $title = '';
    public $description = '';
    public $category = 'other';
    public $priority = 'normal';
    public $assigned_to = '';
    public $status = 'pending';
    
    // Scheduling
    public $preferred_date = '';
    public $preferred_time_start = '';
    public $preferred_time_end = '';
    public $scheduled_date = '';
    public $scheduled_time_start = '';
    public $scheduled_time_end = '';
    
    // Access
    public $access_instructions = '';
    public $tenant_present_required = false;
    public $keys_required = false;
    
    // Cost
    public $estimated_cost = '';
    public $cost_approval_required = false;
    
    // Recurring
    public $is_recurring = false;
    public $recurring_frequency = '';
    public $next_due_date = '';
    
    // Units for selected property
    public $units = [];

    public function mount()
    {
        // Set default preferred date to today
        $this->preferred_date = date('Y-m-d');
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

    public function save()
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
            'cost_approval_required' => 'boolean',
            'is_recurring' => 'boolean',
            'recurring_frequency' => 'nullable|in:weekly,monthly,quarterly,semi_annual,annual',
            'next_due_date' => 'nullable|date',
        ]);

        $validated['requested_by'] = Auth::id();

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
            'recurring_frequency',
            'next_due_date'
        ];
        
        foreach ($nullableFields as $field) {
            if (isset($validated[$field]) && $validated[$field] === '') {
                $validated[$field] = null;
            }
        }

        $request = MaintenanceRequest::create($validated);

        session()->flash('message', 'Maintenance request created successfully.');
        return redirect()->route('admin.maintenance-requests.show', $request);
    }

    public function render()
    {
        $properties = Property::select('id', 'name')->orderBy('name')->get();
        $tenants = Tenant::with('user')->get();
        $users = User::select('id', 'name')->orderBy('name')->get();

        return view('livewire.admin.maintenance-requests.create', [
            'properties' => $properties,
            'tenants' => $tenants,
            'users' => $users,
        ]);
    }
}
