<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Http\Resources\NotificationResource;
use Illuminate\Http\Request;

/**
 * @group Notification Management
 * 
 * APIs for managing user notifications
 * 
 * @authenticated
 */
class NotificationController extends Controller
{
    /**
     * List notifications
     * 
     * Retrieve user's notifications with filtering options.
     * Users see only their own notifications.
     * 
     * @queryParam type string Filter by type (info, warning, alert, success, payment_reminder, maintenance_update, contract_expiry). Example: payment_reminder
     * @queryParam is_read boolean Filter by read status (0 for unread, 1 for read). Example: 0
     * @queryParam page integer Page number. Example: 1
     * @queryParam per_page integer Items per page (max 100). Example: 20
     * 
     * @response 200 scenario="Success" {
     *   "data": [
     *     {
     *       "id": "9d5e8c7a-1234-5678-90ab-cdef12345678",
     *       "user_id": "9d5e8c7a-5555-6666-7777-888888888888",
     *       "type": "payment_reminder",
     *       "title": "Payment Due Reminder",
     *       "message": "Your rent payment is due in 3 days",
     *       "is_read": false,
     *       "created_at": "2025-11-08T10:00:00.000000Z"
     *     }
     *   ]
     * }
     */
    public function index(Request $request)
    {
        $query = Notification::where('user_id', $request->user()->id);
        
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        if ($request->has('is_read')) {
            $query->where('is_read', $request->boolean('is_read'));
        }
        
        $perPage = min($request->input('per_page', 20), 100);
        $notifications = $query->orderBy('created_at', 'desc')->paginate($perPage);
        
        return NotificationResource::collection($notifications);
    }

    /**
     * Send a new notification
     * 
     * Create and send a notification to a user.
     * Only admins and landlords can send notifications.
     * 
     * @bodyParam user_id string required Recipient user UUID. Example: 9d5e8c7a-5555-6666-7777-888888888888
     * @bodyParam type string required Notification type (info, warning, alert, success, payment_reminder, maintenance_update, contract_expiry). Example: payment_reminder
     * @bodyParam title string required Notification title. Example: Payment Due Reminder
     * @bodyParam message text required Notification message. Example: Your rent payment for unit A-101 is due on 2025-11-15
     * @bodyParam action_url string URL for action button. Example: /payments/123
     * @bodyParam metadata object Additional metadata. Example: {"invoice_id": "9d5e8c7a-1111-2222-3333-444444444444", "amount": 2500}
     * 
     * @response 201 scenario="Created" {
     *   "data": {
     *     "id": "9d5e8c7a-1234-5678-90ab-cdef12345678",
     *     "type": "payment_reminder",
     *     "title": "Payment Due Reminder",
     *     "is_read": false
     *   }
     * }
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:info,warning,alert,success,payment_reminder,maintenance_update,contract_expiry',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'action_url' => 'nullable|string|max:500',
            'metadata' => 'nullable|array',
        ]);
        
        $notification = Notification::create($validated);
        
        // TODO: Send push notification, email, or SMS based on user preferences
        
        return new NotificationResource($notification);
    }

    /**
     * Get notification details
     * 
     * Retrieve a specific notification and mark it as read.
     * 
     * @urlParam id string required Notification UUID. Example: 9d5e8c7a-1234-5678-90ab-cdef12345678
     * 
     * @response 200 scenario="Success" {
     *   "data": {
     *     "id": "9d5e8c7a-1234-5678-90ab-cdef12345678",
     *     "type": "payment_reminder",
     *     "title": "Payment Due Reminder",
     *     "message": "Your rent payment is due in 3 days",
     *     "is_read": true,
     *     "read_at": "2025-11-08T11:30:00.000000Z"
     *   }
     * }
     */
    public function show(string $id)
    {
        $notification = Notification::where('user_id', auth()->id())->findOrFail($id);
        
        // Mark as read if not already read
        if (!$notification->is_read) {
            $notification->update([
                'is_read' => true,
                'read_at' => now()
            ]);
        }
        
        return new NotificationResource($notification);
    }

    /**
     * Mark notification as read
     * 
     * Manually mark a notification as read.
     * 
     * @urlParam id string required Notification UUID. Example: 9d5e8c7a-1234-5678-90ab-cdef12345678
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
        $notification = Notification::where('user_id', auth()->id())->findOrFail($id);
        
        $notification->update([
            'is_read' => true,
            'read_at' => now()
        ]);
        
        return new NotificationResource($notification);
    }

    /**
     * Delete notification
     * 
     * Delete a notification from the user's list.
     * 
     * @urlParam id string required Notification UUID. Example: 9d5e8c7a-1234-5678-90ab-cdef12345678
     * 
     * @response 200 scenario="Success" {
     *   "message": "Notification deleted successfully."
     * }
     */
    public function destroy(string $id)
    {
        $notification = Notification::where('user_id', auth()->id())->findOrFail($id);
        
        $notification->delete();
        
        return response()->json([
            'message' => __('messages.notification_deleted')
        ]);
    }
}
