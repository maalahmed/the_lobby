<?php

namespace App\Livewire\Admin\Roles;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class Edit extends Component
{
    public $role;
    public $name = '';
    public $selectedPermissions = [];
    public $permissionGroups = [];

    protected $rules = [
        'name' => 'required|string|max:255',
        'selectedPermissions' => 'array',
    ];

    public function mount($role)
    {
        $this->role = Role::with('permissions')->findOrFail($role);
        $this->name = $this->role->name;
        $this->selectedPermissions = $this->role->permissions->pluck('name')->toArray();

        $this->loadPermissions();
    }

    private function loadPermissions()
    {
        $permissions = Permission::all();

        // Group permissions by prefix
        $this->permissionGroups = [
            'Users' => $permissions->filter(fn($p) => str_starts_with($p->name, 'view-users') || str_contains($p->name, '-users')),
            'Properties' => $permissions->filter(fn($p) => str_contains($p->name, 'properties')),
            'Units' => $permissions->filter(fn($p) => str_contains($p->name, 'units')),
            'Tenants' => $permissions->filter(fn($p) => str_contains($p->name, 'tenants')),
            'Contracts' => $permissions->filter(fn($p) => str_contains($p->name, 'contracts')),
            'Financial' => $permissions->filter(fn($p) => str_contains($p->name, 'invoices') || str_contains($p->name, 'payments')),
            'Maintenance' => $permissions->filter(fn($p) => str_contains($p->name, 'maintenance')),
            'Service Providers' => $permissions->filter(fn($p) => str_contains($p->name, 'service-providers')),
            'Communication' => $permissions->filter(fn($p) => str_contains($p->name, 'messages') || str_contains($p->name, 'notifications')),
            'System' => $permissions->filter(fn($p) => str_contains($p->name, 'settings') || str_contains($p->name, 'audit') || str_contains($p->name, 'reports')),
        ];
    }

    public function update()
    {
        // Prevent editing admin role name
        if ($this->role->name === 'admin' && $this->name !== 'admin') {
            session()->flash('error', 'Cannot rename the admin role.');
            return;
        }

        $this->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $this->role->id,
            'selectedPermissions' => 'array',
        ]);

        $this->role->update(['name' => strtolower($this->name)]);

        // Sync permissions
        $this->role->syncPermissions($this->selectedPermissions);

        session()->flash('success', 'Role updated successfully.');

        return redirect()->route('admin.roles.index');
    }

    public function render()
    {
        return view('livewire.admin.roles.edit')
            ->layout('layouts.admin', ['title' => 'Edit Role']);
    }
}
