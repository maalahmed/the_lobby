<?php

namespace App\Livewire\Admin\Messages;

use Livewire\Component;
use App\Models\Message;
use App\Models\User;

class Create extends Component
{
    public $recipient_id;
    public $subject;
    public $body;
    public $context_type;
    public $context_id;

    protected $rules = [
        'recipient_id' => 'required|exists:users,id',
        'subject' => 'required|string|max:255',
        'body' => 'required|string',
        'context_type' => 'nullable|string',
        'context_id' => 'nullable|integer',
    ];

    public function save()
    {
        $this->validate();

        // You'll need to set the sender_id based on your auth
        // For now, using a placeholder - adjust based on your authentication
        $senderId = 1; // Replace with auth()->id() when authentication is ready

        $message = Message::create([
            'sender_id' => $senderId,
            'recipient_id' => $this->recipient_id,
            'thread_id' => null, // New thread
            'subject' => $this->subject,
            'body' => $this->body,
            'context_type' => $this->context_type,
            'context_id' => $this->context_id,
            'is_read' => false,
        ]);

        // Set thread_id to message id for new threads
        $message->update(['thread_id' => $message->id]);

        session()->flash('success', __('Message sent successfully.'));

        return redirect()->route('admin.messages.show', $message);
    }

    public function render()
    {
        $users = User::where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('livewire.admin.messages.create', [
            'users' => $users,
        ]);
    }
}
