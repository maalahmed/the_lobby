<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'sender_id',
        'recipient_id',
        'parent_id',
        'thread_id',
        'subject',
        'body',
        'attachments',
        'context_type',
        'context_id',
        'is_read',
        'read_at',
        'is_archived_by_sender',
        'is_archived_by_recipient',
    ];

    protected $casts = [
        'attachments' => 'json',
        'is_read' => 'boolean',
        'is_archived_by_sender' => 'boolean',
        'is_archived_by_recipient' => 'boolean',
        'read_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the sender of the message.
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Get the recipient of the message.
     */
    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    /**
     * Get the parent message (for replies).
     */
    public function parent()
    {
        return $this->belongsTo(Message::class, 'parent_id');
    }

    /**
     * Get the replies to this message.
     */
    public function replies()
    {
        return $this->hasMany(Message::class, 'parent_id');
    }

    /**
     * Get the owning context model (e.g., Property, MaintenanceRequest).
     */
    public function context()
    {
        return $this->morphTo();
    }

    /**
     * Mark the message as read.
     */
    public function markAsRead()
    {
        $this->update(['is_read' => true, 'read_at' => now()]);
    }
}
