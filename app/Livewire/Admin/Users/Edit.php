<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class Edit extends Component
{
    public $userId;
    public $name;
    public $email;
    public $phone;
    public $password;
    public $password_confirmation;
    public $role;
    public $status;
    public $language_preference;

    public function mount($userId)
    {
        $user = User::with('roles')->findOrFail($userId);

        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->status = $user->status;
        $this->language_preference = $user->language_preference ?? 'en';
        $this->role = $user->roles->first()?->name ?? '';
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->userId,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|exists:roles,name',
            'status' => 'required|in:active,inactive',
            'language_preference' => 'required|in:en,ar',
        ];
    }

    public function save()
    {
        $this->validate();

        $user = User::findOrFail($this->userId);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'status' => $this->status,
            'language_preference' => $this->language_preference,
        ];

        // Only update password if provided
        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        $user->update($data);

        // Update role
        $user->syncRoles([$this->role]);

        session()->flash('message', 'User updated successfully.');

        return redirect()->route('admin.users.index');
    }

    public function render()
    {
        $roles = Role::all();

        return view('livewire.admin.users.edit', [
            'roles' => $roles,
        ])->layout('layouts.admin', ['title' => 'Edit User']);
    }
}
