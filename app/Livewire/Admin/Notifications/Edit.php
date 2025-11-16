<?php

namespace App\Livewire\Admin\Notifications;

use Livewire\Component;
use App\Models\Notification;
use App\Models\User;

class Edit extends Component
{
    public Notification $notification;
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

    public function mount($notification)
    {
        if ($notification instanceof Notification) {
            $this->notification = $notification->load(['user']);
        } else {
            $this->notification = Notification::with(['user'])->findOrFail($notification);
        }

        $this->user_id = $this->notification->user_id;
        $this->type = $this->notification->type;
        $this->title = $this->notification->title;
        $this->title_ar = $this->notification->title_ar;
        $this->message = $this->notification->message;
        $this->message_ar = $this->notification->message_ar;
        $this->data = $this->notification->data ? json_encode($this->notification->data) : null;
        $this->notifiable_type = $this->notification->notifiable_type;
        $this->notifiable_id = $this->notification->notifiable_id;
        $this->channels = $this->notification->channels ?? ['database'];
    }

    public function update()
    {
        $this->validate();

        $this->notification->update([
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
        ]);

        session()->flash('success', __('Notification updated successfully.'));

        return redirect()->route('admin.notifications.show', $this->notification);
    }

    public function delete()
    {
        $this->notification->delete();
        
        session()->flash('success', __('Notification deleted successfully.'));
        
        return redirect()->route('admin.notifications.index');
    }

    public function render()
    {
        $users = User::where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('livewire.admin.notifications.edit', [
            'users' => $users,
        ])->layout('layouts.admin');
    }
}
