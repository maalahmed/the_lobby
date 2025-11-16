<?php

namespace App\Livewire\Admin\Notifications;

use Livewire\Component;
use App\Models\Notification;
use App\Models\User;

class Create extends Component
{
    public $user_id;
    public $type;
    public $title;
    public $message;
    public $data;
    public $notifiable_type;
    public $notifiable_id;
    public $priority = 'normal';
    public $is_actionable = false;
    public $action_url;
    public $channels = [];

    protected $rules = [
        'user_id' => 'required|exists:users,id',
        'type' => 'required|string|max:255',
        'title' => 'required|string|max:255',
        'message' => 'required|string',
        'data' => 'nullable|json',
        'notifiable_type' => 'nullable|string',
        'notifiable_id' => 'nullable|integer',
        'priority' => 'required|in:low,normal,high,urgent',
        'is_actionable' => 'boolean',
        'action_url' => 'nullable|url|max:500',
        'channels' => 'nullable|array',
    ];

    public function mount()
    {
        $this->channels = ['database']; // Default channel
    }

    public function save()
    {
        $this->validate();

        $notification = Notification::create([
            'uuid' => \Illuminate\Support\Str::uuid(),
            'user_id' => $this->user_id,
            'type' => $this->type,
            'title' => $this->title,
            'message' => $this->message,
            'data' => $this->data ? json_decode($this->data, true) : null,
            'notifiable_type' => $this->notifiable_type,
            'notifiable_id' => $this->notifiable_id,
            'priority' => $this->priority,
            'is_actionable' => $this->is_actionable,
            'action_url' => $this->action_url,
            'channels' => $this->channels,
            'sent_at' => now(),
        ]);

        session()->flash('success', __('Notification created successfully.'));

        return redirect()->route('admin.notifications.show', $notification);
    }

    public function render()
    {
        $users = User::where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('livewire.admin.notifications.create', [
            'users' => $users,
        ])->layout('layouts.admin');
    }
}
