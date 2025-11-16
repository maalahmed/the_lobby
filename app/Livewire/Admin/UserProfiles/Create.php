<?php

namespace App\Livewire\Admin\UserProfiles;

use App\Models\UserProfile;
use App\Models\User;
use Livewire\Component;

class Create extends Component
{
    public $user_id;
    public $profile_type = 'tenant';
    public $first_name;
    public $last_name;
    public $date_of_birth;
    public $gender;
    public $nationality;
    public $national_id;
    public $passport_number;
    public $address;
    public $emergency_contact;
    public $preferences;

    protected $rules = [
        'user_id' => 'required|exists:users,id',
        'profile_type' => 'required|in:admin,landlord,tenant,service_provider',
        'first_name' => 'nullable|string|max:100',
        'last_name' => 'nullable|string|max:100',
        'date_of_birth' => 'nullable|date',
        'gender' => 'nullable|in:male,female,other',
        'nationality' => 'nullable|string|max:100',
        'national_id' => 'nullable|string|max:50',
        'passport_number' => 'nullable|string|max:50',
        'address' => 'nullable|string',
        'emergency_contact' => 'nullable|string',
        'preferences' => 'nullable|string',
    ];

    public function save()
    {
        $this->validate();

        UserProfile::create([
            'user_id' => $this->user_id,
            'profile_type' => $this->profile_type,
            'first_name' => $this->first_name ?: null,
            'last_name' => $this->last_name ?: null,
            'date_of_birth' => $this->date_of_birth ?: null,
            'gender' => $this->gender ?: null,
            'nationality' => $this->nationality ?: null,
            'national_id' => $this->national_id ?: null,
            'passport_number' => $this->passport_number ?: null,
            'address' => $this->address ? json_decode($this->address, true) : null,
            'emergency_contact' => $this->emergency_contact ? json_decode($this->emergency_contact, true) : null,
            'preferences' => $this->preferences ? json_decode($this->preferences, true) : null,
        ]);

        session()->flash('success', 'User profile created successfully.');
        return redirect()->route('admin.user-profiles.index');
    }

    public function render()
    {
        $users = User::whereDoesntHave('profile')
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('livewire.admin.user-profiles.create', [
            'users' => $users,
        ]);
    }
}
