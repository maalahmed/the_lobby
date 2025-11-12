<?php

namespace App\Livewire\Admin\ServiceProviders;

use App\Models\ServiceProvider;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Create extends Component
{
    public $user_id;
    public $company_name;
    public $business_registration;
    public $tax_number;
    public $primary_contact_name;
    public $primary_contact_phone;
    public $primary_contact_email;
    public $office_address;
    public $service_categories;
    public $specializations;
    public $service_areas;
    public $years_in_business;
    public $team_size;
    public $emergency_services = false;
    public $payment_terms;
    public $status = 'active';
    public $notes;

    protected $rules = [
        'user_id' => 'required|exists:users,id',
        'company_name' => 'required|string|max:255',
        'business_registration' => 'nullable|string|max:100',
        'tax_number' => 'nullable|string|max:100',
        'primary_contact_name' => 'nullable|string|max:255',
        'primary_contact_phone' => 'nullable|string|max:20',
        'primary_contact_email' => 'nullable|email|max:255',
        'office_address' => 'nullable|string',
        'service_categories' => 'nullable|string',
        'specializations' => 'nullable|string',
        'service_areas' => 'nullable|string',
        'years_in_business' => 'nullable|integer|min:0|max:255',
        'team_size' => 'nullable|integer|min:0|max:65535',
        'emergency_services' => 'boolean',
        'payment_terms' => 'nullable|string|max:255',
        'status' => 'required|in:active,inactive,suspended,blacklisted',
        'notes' => 'nullable|string',
    ];

    public function save()
    {
        $this->validate();

        ServiceProvider::create([
            'user_id' => $this->user_id,
            'company_name' => $this->company_name,
            'business_registration' => $this->business_registration ?: null,
            'tax_number' => $this->tax_number ?: null,
            'primary_contact_name' => $this->primary_contact_name ?: null,
            'primary_contact_phone' => $this->primary_contact_phone ?: null,
            'primary_contact_email' => $this->primary_contact_email ?: null,
            'office_address' => $this->office_address ? json_decode($this->office_address, true) : null,
            'service_categories' => $this->service_categories ? explode(',', $this->service_categories) : null,
            'specializations' => $this->specializations ? explode(',', $this->specializations) : null,
            'service_areas' => $this->service_areas ? explode(',', $this->service_areas) : null,
            'years_in_business' => $this->years_in_business ?: null,
            'team_size' => $this->team_size ?: null,
            'emergency_services' => $this->emergency_services,
            'payment_terms' => $this->payment_terms ?: null,
            'status' => $this->status,
            'notes' => $this->notes ?: null,
        ]);

        session()->flash('success', 'Service provider created successfully.');
        return redirect()->route('admin.service-providers.index');
    }

    public function render()
    {
        $users = User::where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('livewire.admin.service-providers.create', [
            'users' => $users,
        ])->layout('layouts.admin');
    }
}
