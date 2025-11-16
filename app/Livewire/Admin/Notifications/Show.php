<?php

namespace App\Livewire\Admin\Notifications;

use Livewire\Component;
use App\Models\Notification;

class Show extends Component
{
    public Notification $notification;

    public function mount($notification)
    {
        if ($notification instanceof Notification) {
            $this->notification = $notification->load(['user', 'notifiable']);
        } else {
            $this->notification = Notification::with(['user', 'notifiable'])->findOrFail($notification);
        }

        // Auto mark as read when viewing
        if ($this->notification->isUnread()) {
            $this->notification->markAsRead();
        }
    }

    public function delete()
    {
        $this->notification->delete();
        
        session()->flash('success', __('Notification deleted successfully.'));
        
        return redirect()->route('admin.notifications.index');
    }

    public function render()
    {
        return view('livewire.admin.notifications.show');
    }
}
