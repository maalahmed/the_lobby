<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class Create extends Component
{
    public $name;
    public $email;
    public $phone;
    public $password;
    public $password_confirmation;
    public $role;
    public $status = 'active';
    public $language_preference = 'en';

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|exists:roles,name',
            'status' => 'required|in:active,inactive',
            'language_preference' => 'required|in:en,ar',
        ];
    }

    public function save()
    {
        $this->validate();

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'password' => Hash::make($this->password),
            'status' => $this->status,
            'language_preference' => $this->language_preference,
        ]);

        $user->assignRole($this->role);

        session()->flash('message', 'User created successfully.');

        return redirect()->route('admin.users.index');
    }

    public function render()
    {
        $roles = Role::all();

        return view('livewire.admin.users.create', [
            'roles' => $roles,
        ])->layout('layouts.admin');
    }
}
