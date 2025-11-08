<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Http\Resources\MessageResource;
use Illuminate\Http\Request;

/**
 * @group Message Management
 * 
 * APIs for managing messages and communication between users
 * 
 * @authenticated
 */
class MessageController extends Controller
{
    /**
     * List messages
     * 
     * Retrieve user's messages (inbox and sent).
     * Users see only their sent or received messages.
     * 
     * @queryParam conversation_with string Filter by conversation partner user UUID. Example: 9d5e8c7a-1111-2222-3333-444444444444
     * @queryParam type string Filter by type (sent, received). Example: received
     * @queryParam is_read boolean Filter by read status (0 for unread, 1 for read). Example: 0
     * @queryParam page integer Page number. Example: 1
     * @queryParam per_page integer Items per page (max 100). Example: 20
     * 
     * @response 200 scenario="Success" {
     *   "data": [
     *     {
     *       "id": "9d5e8c7a-1234-5678-90ab-cdef12345678",
     *       "sender_id": "9d5e8c7a-5555-6666-7777-888888888888",
     *       "receiver_id": "9d5e8c7a-1111-2222-3333-444444444444",
     *       "subject": "Regarding rent payment",
     *       "message": "Hello, I wanted to discuss the upcoming rent payment...",
     *       "is_read": false,
     *       "created_at": "2025-11-08T10:00:00.000000Z",
     *       "sender": {
     *         "name": "John Doe"
     *       }
     *     }
     *   ]
     * }
     */
    public function index(Request $request)
    {
        $userId = $request->user()->id;
        
        $query = Message::where(function($q) use ($userId) {
            $q->where('sender_id', $userId)
              ->orWhere('receiver_id', $userId);
        })->with(['sender', 'receiver']);
        
        if ($request->filled('conversation_with')) {
            $query->where(function($q) use ($userId, $request) {
                $q->where(function($q2) use ($userId, $request) {
                    $q2->where('sender_id', $userId)
                       ->where('receiver_id', $request->conversation_with);
                })->orWhere(function($q2) use ($userId, $request) {
                    $q2->where('sender_id', $request->conversation_with)
                       ->where('receiver_id', $userId);
                });
            });
        }
        
        if ($request->filled('type')) {
            if ($request->type === 'sent') {
                $query->where('sender_id', $userId);
            } elseif ($request->type === 'received') {
                $query->where('receiver_id', $userId);
            }
        }
        
        if ($request->has('is_read')) {
            $query->where('is_read', $request->boolean('is_read'));
        }
        
        $perPage = min($request->input('per_page', 20), 100);
        $messages = $query->orderBy('created_at', 'desc')->paginate($perPage);
        
        return MessageResource::collection($messages);
    }

    /**
     * Send a new message
     * 
     * Send a message to another user in the system.
     * 
     * @bodyParam receiver_id string required Recipient user UUID. Example: 9d5e8c7a-1111-2222-3333-444444444444
     * @bodyParam subject string Message subject. Example: Regarding rent payment
     * @bodyParam message text required Message content. Example: Hello, I wanted to discuss the upcoming rent payment for unit A-101
     * @bodyParam parent_message_id string Parent message UUID if this is a reply. Example: 9d5e8c7a-2222-3333-4444-555555555555
     * @bodyParam related_to_type string Related entity type (property, unit, contract, invoice, maintenance). Example: invoice
     * @bodyParam related_to_id string Related entity UUID. Example: 9d5e8c7a-3333-4444-5555-666666666666
     * @bodyParam attachments array Message attachments. Example: ["messages/attachment1.pdf"]
     * 
     * @response 201 scenario="Created" {
     *   "data": {
     *     "id": "9d5e8c7a-1234-5678-90ab-cdef12345678",
     *     "subject": "Regarding rent payment",
     *     "is_read": false,
     *     "created_at": "2025-11-08T11:00:00.000000Z"
     *   }
     * }
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string',
            'parent_message_id' => 'nullable|exists:messages,id',
            'related_to_type' => 'nullable|in:property,unit,contract,invoice,maintenance',
            'related_to_id' => 'nullable|string',
            'attachments' => 'nullable|array',
        ]);
        
        $validated['sender_id'] = $request->user()->id;
        
        $message = Message::create($validated);
        
        // TODO: Send notification to receiver
        
        return new MessageResource($message);
    }

    /**
     * Get message details
     * 
     * Retrieve a specific message and mark it as read if user is the receiver.
     * 
     * @urlParam id string required Message UUID. Example: 9d5e8c7a-1234-5678-90ab-cdef12345678
     * 
     * @response 200 scenario="Success" {
     *   "data": {
     *     "id": "9d5e8c7a-1234-5678-90ab-cdef12345678",
     *     "sender": {
     *       "id": "9d5e8c7a-5555-6666-7777-888888888888",
     *       "name": "John Doe"
     *     },
     *     "receiver": {
     *       "id": "9d5e8c7a-1111-2222-3333-444444444444",
     *       "name": "Jane Smith"
     *     },
     *     "subject": "Regarding rent payment",
     *     "message": "Full message content...",
     *     "is_read": true,
     *     "read_at": "2025-11-08T12:00:00.000000Z"
     *   }
     * }
     */
    public function show(string $id)
    {
        $userId = auth()->id();
        
        $message = Message::where(function($q) use ($userId) {
            $q->where('sender_id', $userId)
              ->orWhere('receiver_id', $userId);
        })->with(['sender', 'receiver', 'parentMessage'])->findOrFail($id);
        
        // Mark as read if user is the receiver and message is unread
        if ($message->receiver_id === $userId && !$message->is_read) {
            $message->update([
                'is_read' => true,
                'read_at' => now()
            ]);
        }
        
        return new MessageResource($message);
    }

    /**
     * Mark message as read
     * 
     * Manually mark a received message as read.
     * 
     * @urlParam id string required Message UUID. Example: 9d5e8c7a-1234-5678-90ab-cdef12345678
     * 
     * @response 200 scenario="Success" {
     *   "data": {
     *     "id": "9d5e8c7a-1234-5678-90ab-cdef12345678",
     *     "is_read": true
     *   }
     * }
     */
    public function update(Request $request, string $id)
    {
        $message = Message::where('receiver_id', auth()->id())->findOrFail($id);
        
        $message->update([
            'is_read' => true,
            'read_at' => now()
        ]);
        
        return new MessageResource($message);
    }

    /**
     * Delete message
     * 
     * Delete a message. Users can delete messages they sent or received.
     * Note: This performs a soft delete to maintain message history.
     * 
     * @urlParam id string required Message UUID. Example: 9d5e8c7a-1234-5678-90ab-cdef12345678
     * 
     * @response 200 scenario="Success" {
     *   "message": "Message deleted successfully."
     * }
     */
    public function destroy(string $id)
    {
        $userId = auth()->id();
        
        $message = Message::where(function($q) use ($userId) {
            $q->where('sender_id', $userId)
              ->orWhere('receiver_id', $userId);
        })->findOrFail($id);
        
        $message->delete();
        
        return response()->json([
            'message' => __('messages.message_deleted')
        ]);
    }
}
