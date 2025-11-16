<?php

namespace App\Livewire\Admin\Notifications;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Notification;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $typeFilter = '';
    public $statusFilter = ''; // read, unread, sent, failed

    protected $queryString = ['search', 'typeFilter', 'statusFilter'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingTypeFilter()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function delete($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->delete();
        
        session()->flash('success', __('Notification deleted successfully.'));
    }

    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->markAsRead();
        
        session()->flash('success', __('Notification marked as read.'));
    }

    public function render()
    {
        $query = Notification::query()->with(['user']);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('message', 'like', '%' . $this->search . '%')
                  ->orWhere('type', 'like', '%' . $this->search . '%')
                  ->orWhereHas('user', function ($q) {
                      $q->where('name', 'like', '%' . $this->search . '%');
                  });
            });
        }

        if ($this->typeFilter) {
            $query->where('type', $this->typeFilter);
        }

        if ($this->statusFilter === 'read') {
            $query->whereNotNull('read_at');
        } elseif ($this->statusFilter === 'unread') {
            $query->whereNull('read_at');
        } elseif ($this->statusFilter === 'sent') {
            $query->whereNotNull('sent_at')->whereNull('failed_at');
        } elseif ($this->statusFilter === 'failed') {
            $query->whereNotNull('failed_at');
        }

        $notifications = $query->latest()->paginate(15);
        
        $types = Notification::distinct()->pluck('type');

        return view('livewire.admin.notifications.index', [
            'notifications' => $notifications,
            'types' => $types,
        ]);
    }
}
