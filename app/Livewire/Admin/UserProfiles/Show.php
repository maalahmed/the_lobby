<?php

namespace App\Livewire\Admin\UserProfiles;

use App\Models\UserProfile;
use Livewire\Component;

class Show extends Component
{
    public UserProfile $profile;

    public function mount($profile)
    {
        $this->profile = UserProfile::with(['user'])->findOrFail($profile);
    }

    public function delete()
    {
        $this->profile->delete();
        session()->flash('success', 'User profile deleted successfully.');
        return redirect()->route('admin.user-profiles.index');
    }

    public function render()
    {
        return view('livewire.admin.user-profiles.show')->layout('layouts.admin');
    }
}
