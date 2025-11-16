<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $roleFilter = '';
    public $statusFilter = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    protected $queryString = [
        'search' => ['except' => ''],
        'roleFilter' => ['except' => ''],
        'statusFilter' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingRoleFilter()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function toggleStatus($userId)
    {
        $user = User::findOrFail($userId);

        // Don't allow deactivating yourself
        if ($user->id === auth()->id()) {
            session()->flash('error', 'You cannot deactivate your own account.');
            return;
        }

        $user->status = $user->status === 'active' ? 'inactive' : 'active';
        $user->save();

        session()->flash('message', 'User status updated successfully.');
    }

    public function deleteUser($userId)
    {
        $user = User::findOrFail($userId);

        // Don't allow deleting yourself
        if ($user->id === auth()->id()) {
            session()->flash('error', 'You cannot delete your own account.');
            return;
        }

        // Check for related records
        if ($user->properties()->count() > 0 ||
            $user->leaseContracts()->count() > 0 ||
            $user->maintenanceRequests()->count() > 0) {
            session()->flash('error', 'Cannot delete user with associated records.');
            return;
        }

        $user->delete();
        session()->flash('message', 'User deleted successfully.');
    }

    public function render()
    {
        $query = User::query()
            ->with('roles');

        // Search
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%')
                    ->orWhere('phone', 'like', '%' . $this->search . '%');
            });
        }

        // Filter by role
        if ($this->roleFilter) {
            $query->role($this->roleFilter);
        }

        // Filter by status
        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        // Sort
        $query->orderBy($this->sortField, $this->sortDirection);

        $users = $query->paginate(10);

        // Stats
        $stats = [
            'total' => User::count(),
            'active' => User::where('status', 'active')->count(),
            'inactive' => User::where('status', 'inactive')->count(),
            'admins' => User::role('admin')->count(),
            'landlords' => User::role('landlord')->count(),
            'tenants' => User::role('tenant')->count(),
            'providers' => User::role('service_provider')->count(),
        ];

        $roles = Role::all();

        return view('livewire.admin.users.index', [
            'users' => $users,
            'stats' => $stats,
            'roles' => $roles,
        ])->layout('layouts.admin');
    }
}
