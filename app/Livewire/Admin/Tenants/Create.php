<?php

namespace App\Livewire\Admin\Tenants;

use App\Models\Tenant;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Create extends Component
{
    // User fields
    public $user_id = '';
    public $create_new_user = true;
    public $name = '';
    public $email = '';
    public $phone = '';
    public $password = '';

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

    protected function rules()
    {
        $rules = [
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

        if ($this->create_new_user) {
            $rules['name'] = 'required|string|max:255';
            $rules['email'] = 'required|email|unique:users,email';
            $rules['phone'] = 'required|string|max:20';
            $rules['password'] = 'required|string|min:8';
        } else {
            $rules['user_id'] = 'required|exists:users,id';
        }

        return $rules;
    }

    public function save()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            // Create or get user
            if ($this->create_new_user) {
                $user = User::create([
                    'name' => $this->name,
                    'email' => $this->email,
                    'phone' => $this->phone,
                    'password' => bcrypt($this->password),
                    'role' => 'tenant',
                ]);
                $userId = $user->id;
            } else {
                $userId = $this->user_id;
            }

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

            // Create tenant
            Tenant::create([
                'user_id' => $userId,
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

            DB::commit();

            session()->flash('success', __('Tenant created successfully'));
            return redirect()->route('admin.tenants.index');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create tenant: ' . $e->getMessage());
            session()->flash('error', __('Failed to create tenant. Please try again.'));
        }
    }

    public function render()
    {
        $users = User::whereDoesntHave('tenant')
            ->where('role', 'tenant')
            ->select('id', 'name', 'email')
            ->get();

        /** @var \Illuminate\View\View $view */
        $view = view('livewire.admin.tenants.create', [
            'users' => $users,
        ]);
        
        return $view->layout('layouts.admin', ['title' => __('Create New Tenant')]);
    }
}
