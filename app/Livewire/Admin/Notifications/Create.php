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
    public $title_ar;
    public $message;
    public $message_ar;
    public $data;
    public $notifiable_type;
    public $notifiable_id;
    public $channels = [];

    protected $rules = [
        'user_id' => 'required|exists:users,id',
        'type' => 'required|string|max:255',
        'title' => 'required|string|max:255',
        'title_ar' => 'nullable|string|max:255',
        'message' => 'required|string',
        'message_ar' => 'nullable|string',
        'data' => 'nullable|json',
        'notifiable_type' => 'nullable|string',
        'notifiable_id' => 'nullable|integer',
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
            'user_id' => $this->user_id,
            'type' => $this->type,
            'title' => $this->title,
            'title_ar' => $this->title_ar,
            'message' => $this->message,
            'message_ar' => $this->message_ar,
            'data' => $this->data ? json_decode($this->data, true) : null,
            'notifiable_type' => $this->notifiable_type,
            'notifiable_id' => $this->notifiable_id,
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
