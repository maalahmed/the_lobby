<?php

namespace App\Livewire\Admin\Messages;

use Livewire\Component;
use App\Models\Message;

class Show extends Component
{
    public Message $message;
    public $replyBody = '';

    public function mount($message)
    {
        if ($message instanceof Message) {
            $this->message = $message->load(['sender', 'recipient', 'replies.sender', 'replies.recipient', 'context']);
        } else {
            $this->message = Message::with(['sender', 'recipient', 'replies.sender', 'replies.recipient', 'context'])
                ->findOrFail($message);
        }

        // Mark as read if the current user is the recipient
        if (!$this->message->is_read) {
            $this->message->markAsRead();
        }
    }

    public function sendReply()
    {
        $this->validate([
            'replyBody' => 'required|string',
        ]);

        // Create reply
        $reply = Message::create([
            'sender_id' => 1, // Replace with auth()->id()
            'recipient_id' => $this->message->sender_id,
            'parent_id' => $this->message->id,
            'thread_id' => $this->message->thread_id ?? $this->message->id,
            'subject' => 'Re: ' . $this->message->subject,
            'body' => $this->replyBody,
            'is_read' => false,
        ]);

        $this->replyBody = '';
        
        // Reload message with replies
        $this->message->load(['replies.sender', 'replies.recipient']);

        session()->flash('success', __('Reply sent successfully.'));
    }

    public function delete()
    {
        $this->message->delete();
        
        session()->flash('success', __('Message deleted successfully.'));
        
        return redirect()->route('admin.messages.index');
    }

    public function render()
    {
        return view('livewire.admin.messages.show');
    }
}
