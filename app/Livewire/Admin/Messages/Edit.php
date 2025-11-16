<?php

namespace App\Livewire\Admin\Messages;

use Livewire\Component;
use App\Models\Message;
use App\Models\User;

class Edit extends Component
{
    public Message $message;
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

    public function mount($message)
    {
        if ($message instanceof Message) {
            $this->message = $message->load(['sender', 'recipient']);
        } else {
            $this->message = Message::with(['sender', 'recipient'])->findOrFail($message);
        }

        $this->recipient_id = $this->message->recipient_id;
        $this->subject = $this->message->subject;
        $this->body = $this->message->body;
        $this->context_type = $this->message->context_type;
        $this->context_id = $this->message->context_id;
    }

    public function update()
    {
        $this->validate();

        $this->message->update([
            'recipient_id' => $this->recipient_id,
            'subject' => $this->subject,
            'body' => $this->body,
            'context_type' => $this->context_type,
            'context_id' => $this->context_id,
        ]);

        session()->flash('success', __('Message updated successfully.'));

        return redirect()->route('admin.messages.show', $this->message);
    }

    public function delete()
    {
        $this->message->delete();
        
        session()->flash('success', __('Message deleted successfully.'));
        
        return redirect()->route('admin.messages.index');
    }

    public function render()
    {
        $users = User::where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('livewire.admin.messages.edit', [
            'users' => $users,
        ]);
    }
}
