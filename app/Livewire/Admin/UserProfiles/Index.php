<?php

namespace App\Livewire\Admin\UserProfiles;

use App\Models\UserProfile;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $profileTypeFilter = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'profileTypeFilter' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingProfileTypeFilter()
    {
        $this->resetPage();
    }

    public function delete($id)
    {
        $profile = UserProfile::findOrFail($id);
        $profile->delete();
        session()->flash('success', 'User profile deleted successfully.');
    }

    public function render()
    {
        $profiles = UserProfile::query()
            ->with(['user'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('first_name', 'like', '%' . $this->search . '%')
                        ->orWhere('last_name', 'like', '%' . $this->search . '%')
                        ->orWhere('national_id', 'like', '%' . $this->search . '%')
                        ->orWhere('passport_number', 'like', '%' . $this->search . '%')
                        ->orWhereHas('user', function ($q) {
                            $q->where('name', 'like', '%' . $this->search . '%')
                                ->orWhere('email', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->profileTypeFilter, function ($query) {
                $query->where('profile_type', $this->profileTypeFilter);
            })
            ->latest()
            ->paginate(10);

        return view('livewire.admin.user-profiles.index', [
            'profiles' => $profiles,
        ])->layout('layouts.admin');
    }
}
