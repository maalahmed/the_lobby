<?php

namespace App\Livewire\Admin\Messages;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Message;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $filterType = ''; // inbox, sent, archived

    protected $queryString = ['search', 'filterType'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterType()
    {
        $this->resetPage();
    }

    public function delete($id)
    {
        $message = Message::findOrFail($id);
        $message->delete();
        
        session()->flash('success', __('Message deleted successfully.'));
    }

    public function archive($id)
    {
        $message = Message::findOrFail($id);
        
        // Archive for the current user (assuming admin user ID is 1 or get from auth)
        // You may need to adjust this based on your authentication setup
        $message->update(['is_archived_by_sender' => true]);
        
        session()->flash('success', __('Message archived successfully.'));
    }

    public function render()
    {
        $query = Message::query()
            ->with(['sender', 'recipient'])
            ->whereNull('parent_id'); // Only show parent messages (not replies)

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('subject', 'like', '%' . $this->search . '%')
                  ->orWhere('body', 'like', '%' . $this->search . '%')
                  ->orWhereHas('sender', function ($q) {
                      $q->where('name', 'like', '%' . $this->search . '%');
                  })
                  ->orWhereHas('recipient', function ($q) {
                      $q->where('name', 'like', '%' . $this->search . '%');
                  });
            });
        }

        if ($this->filterType === 'sent') {
            // Adjust sender_id based on your auth
            $query->where('sender_id', '!=', null);
        } elseif ($this->filterType === 'archived') {
            $query->where(function ($q) {
                $q->where('is_archived_by_sender', true)
                  ->orWhere('is_archived_by_recipient', true);
            });
        }

        $messages = $query->latest()->paginate(15);

        return view('livewire.admin.messages.index', [
            'messages' => $messages,
        ])->layout('layouts.admin');
    }
}
