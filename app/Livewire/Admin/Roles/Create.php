<?php

namespace App\Livewire\Admin\Roles;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class Create extends Component
{
    public $name = '';
    public $selectedPermissions = [];
    public $permissionGroups = [];

    protected $rules = [
        'name' => 'required|string|max:255|unique:roles,name',
        'selectedPermissions' => 'array',
    ];

    public function mount()
    {
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

    public function save()
    {
        $this->validate();

        $role = Role::create(['name' => strtolower($this->name)]);
        
        if (!empty($this->selectedPermissions)) {
            $role->givePermissionTo($this->selectedPermissions);
        }

        session()->flash('success', 'Role created successfully.');
        
        return redirect()->route('admin.roles.index');
    }

    public function render()
    {
        return view('livewire.admin.roles.create')
            ->layout('layouts.admin', ['title' => 'Create Role']);
    }
}
