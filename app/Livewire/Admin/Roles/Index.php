<?php

namespace App\Livewire\Admin\Roles;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $deleteId;

    protected $queryString = ['search'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
    }

    public function delete()
    {
        $role = Role::findOrFail($this->deleteId);
        
        // Prevent deletion of admin role
        if ($role->name === 'admin') {
            session()->flash('error', 'Cannot delete the admin role.');
            return;
        }

        // Check if role has users
        if ($role->users()->count() > 0) {
            session()->flash('error', 'Cannot delete role that has users assigned to it.');
            return;
        }

        $role->delete();
        
        session()->flash('success', 'Role deleted successfully.');
        $this->deleteId = null;
    }

    public function render()
    {
        $roles = Role::when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->withCount('permissions', 'users')
            ->paginate(10);

        return view('livewire.admin.roles.index', [
            'roles' => $roles,
        ])->layout('layouts.admin', ['title' => 'Roles Management']);
    }
}
