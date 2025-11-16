<?php

namespace App\Livewire\Admin\Tenants;

use App\Models\Tenant;
use Livewire\Component;
use Illuminate\Support\Facades\Log;

class Edit extends Component
{
    public Tenant $tenant;

    // Tenant fields
    public $occupation = '';
    public $employer = '';
    public $monthly_income = '';
    public $credit_score = '';
    public $background_check_status = 'not_required';
    public $status = 'prospect';
    public $notes = '';

    // Emergency contact
    public $emergency_name = '';
    public $emergency_phone = '';
    public $emergency_relationship = '';

    // References
    public $reference1_name = '';
    public $reference1_phone = '';
    public $reference1_relationship = '';
    public $reference2_name = '';
    public $reference2_phone = '';
    public $reference2_relationship = '';

    public function mount(Tenant $tenant)
    {
        $this->tenant = $tenant;
        
        // Populate form fields
        $this->occupation = $this->tenant->occupation;
        $this->employer = $this->tenant->employer;
        $this->monthly_income = $this->tenant->monthly_income;
        $this->credit_score = $this->tenant->credit_score;
        $this->background_check_status = $this->tenant->background_check_status ?? 'not_required';
        $this->status = $this->tenant->status;
        $this->notes = $this->tenant->notes;

        // Populate emergency contact
        if ($this->tenant->emergency_contact) {
            $emergency = $this->tenant->emergency_contact;
            $this->emergency_name = $emergency['name'] ?? '';
            $this->emergency_phone = $emergency['phone'] ?? '';
            $this->emergency_relationship = $emergency['relationship'] ?? '';
        }

        // Populate references
        if ($this->tenant->references && is_array($this->tenant->references)) {
            if (isset($this->tenant->references[0])) {
                $this->reference1_name = $this->tenant->references[0]['name'] ?? '';
                $this->reference1_phone = $this->tenant->references[0]['phone'] ?? '';
                $this->reference1_relationship = $this->tenant->references[0]['relationship'] ?? '';
            }
            if (isset($this->tenant->references[1])) {
                $this->reference2_name = $this->tenant->references[1]['name'] ?? '';
                $this->reference2_phone = $this->tenant->references[1]['phone'] ?? '';
                $this->reference2_relationship = $this->tenant->references[1]['relationship'] ?? '';
            }
        }
    }

    protected function rules()
    {
        return [
            'occupation' => 'nullable|string|max:255',
            'employer' => 'nullable|string|max:255',
            'monthly_income' => 'nullable|numeric|min:0',
            'credit_score' => 'nullable|integer|min:300|max:850',
            'background_check_status' => 'required|in:pending,passed,failed,not_required',
            'status' => 'required|in:active,inactive,blacklisted,prospect',
            'notes' => 'nullable|string',
            'emergency_name' => 'nullable|string|max:255',
            'emergency_phone' => 'nullable|string|max:20',
            'emergency_relationship' => 'nullable|string|max:100',
            'reference1_name' => 'nullable|string|max:255',
            'reference1_phone' => 'nullable|string|max:20',
            'reference1_relationship' => 'nullable|string|max:100',
            'reference2_name' => 'nullable|string|max:255',
            'reference2_phone' => 'nullable|string|max:20',
            'reference2_relationship' => 'nullable|string|max:100',
        ];
    }

    public function update()
    {
        $this->validate();

        try {
            // Prepare emergency contact
            $emergencyContact = null;
            if ($this->emergency_name || $this->emergency_phone) {
                $emergencyContact = [
                    'name' => $this->emergency_name,
                    'phone' => $this->emergency_phone,
                    'relationship' => $this->emergency_relationship,
                ];
            }

            // Prepare references
            $references = [];
            if ($this->reference1_name || $this->reference1_phone) {
                $references[] = [
                    'name' => $this->reference1_name,
                    'phone' => $this->reference1_phone,
                    'relationship' => $this->reference1_relationship,
                ];
            }
            if ($this->reference2_name || $this->reference2_phone) {
                $references[] = [
                    'name' => $this->reference2_name,
                    'phone' => $this->reference2_phone,
                    'relationship' => $this->reference2_relationship,
                ];
            }

            // Update tenant
            $this->tenant->update([
                'occupation' => $this->occupation ?: null,
                'employer' => $this->employer ?: null,
                'monthly_income' => $this->monthly_income ?: null,
                'credit_score' => $this->credit_score ?: null,
                'background_check_status' => $this->background_check_status,
                'status' => $this->status,
                'emergency_contact' => $emergencyContact,
                'references' => !empty($references) ? $references : null,
                'notes' => $this->notes ?: null,
            ]);

            session()->flash('success', __('Tenant updated successfully'));
            return redirect()->route('admin.tenants.show', $this->tenant->id);

        } catch (\Exception $e) {
            Log::error('Failed to update tenant: ' . $e->getMessage());
            session()->flash('error', __('Failed to update tenant. Please try again.'));
        }
    }

    public function render()
    {
        /** @var \Illuminate\View\View $view */
        $view = view('livewire.admin.tenants.edit');
        
        return $view->layout('layouts.admin', ['title' => __('Edit Tenant')]);
    }
}
