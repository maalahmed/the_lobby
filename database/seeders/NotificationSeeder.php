<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Notification;
use App\Models\User;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $landlord = User::where('email', 'landlord@thelobby.com')->first();
        $tenant1 = User::where('email', 'tenant1@thelobby.com')->first();
        $tenant2 = User::where('email', 'tenant2@thelobby.com')->first();

        // Payment reminder - Tenant 1 (unread)
        Notification::create([
            'user_id' => $tenant1->id,
            'title_en' => 'Payment Reminder',
            'title_ar' => 'تذكير بالدفع',
            'message_en' => 'Your rent payment of AED 5,000 is due on the 1st of this month.',
            'message_ar' => 'دفع الإيجار الخاص بك بقيمة 5,000 درهم مستحق في الأول من هذا الشهر.',
            'type' => 'payment',
            'priority' => 'high',
            'data' => json_encode(['invoice_id' => 1, 'amount' => 5000]),
            'channels' => json_encode(['database', 'email']),
            'sent_at' => now()->subDays(3),
            'status' => 'sent',
            'read_at' => null,
        ]);

        // Maintenance update - Tenant 1 (read)
        Notification::create([
            'user_id' => $tenant1->id,
            'title_en' => 'Maintenance Request Update',
            'title_ar' => 'تحديث طلب الصيانة',
            'message_en' => 'Your AC maintenance request is now in progress. A technician will visit tomorrow.',
            'message_ar' => 'طلب صيانة التكييف الخاص بك قيد التنفيذ حاليًا. سيزور الفني غدًا.',
            'type' => 'maintenance',
            'priority' => 'medium',
            'data' => json_encode(['request_id' => 1]),
            'channels' => json_encode(['database', 'email', 'push']),
            'sent_at' => now()->subDays(1),
            'status' => 'sent',
            'read_at' => now()->subHours(12),
        ]);

        // Lease expiration warning - Tenant 2
        Notification::create([
            'user_id' => $tenant2->id,
            'title_en' => 'Lease Expiration Warning',
            'title_ar' => 'تحذير انتهاء الإيجار',
            'message_en' => 'Your lease will expire in 60 days. Please contact your landlord for renewal.',
            'message_ar' => 'سينتهي عقد الإيجار الخاص بك خلال 60 يومًا. يرجى الاتصال بالمالك للتجديد.',
            'type' => 'lease',
            'priority' => 'high',
            'data' => json_encode(['lease_id' => 2, 'days_remaining' => 60]),
            'channels' => json_encode(['database', 'email', 'sms']),
            'sent_at' => now()->subHours(6),
            'status' => 'sent',
            'read_at' => null,
        ]);

        // New message notification - Landlord
        Notification::create([
            'user_id' => $landlord->id,
            'title_en' => 'New Message',
            'title_ar' => 'رسالة جديدة',
            'message_en' => 'You have received a new message from your tenant.',
            'message_ar' => 'لقد تلقيت رسالة جديدة من المستأجر الخاص بك.',
            'type' => 'message',
            'priority' => 'low',
            'data' => json_encode(['message_id' => 1]),
            'channels' => json_encode(['database', 'push']),
            'sent_at' => now()->subHours(2),
            'status' => 'sent',
            'read_at' => now()->subHours(1),
        ]);

        // Payment received - Landlord
        Notification::create([
            'user_id' => $landlord->id,
            'title_en' => 'Payment Received',
            'title_ar' => 'تم استلام الدفع',
            'message_en' => 'Payment of AED 5,000 has been received from your tenant.',
            'message_ar' => 'تم استلام دفعة بقيمة 5,000 درهم من المستأجر الخاص بك.',
            'type' => 'payment',
            'priority' => 'medium',
            'data' => json_encode(['payment_id' => 1, 'amount' => 5000]),
            'channels' => json_encode(['database', 'email']),
            'sent_at' => now()->subDays(4),
            'status' => 'sent',
            'read_at' => now()->subDays(4),
        ]);

        // Failed notification - SMS
        Notification::create([
            'user_id' => $tenant1->id,
            'title_en' => 'Invoice Generated',
            'title_ar' => 'تم إنشاء الفاتورة',
            'message_en' => 'A new invoice has been generated for your rent payment.',
            'message_ar' => 'تم إنشاء فاتورة جديدة لدفع الإيجار الخاص بك.',
            'type' => 'invoice',
            'priority' => 'low',
            'data' => json_encode(['invoice_id' => 2]),
            'channels' => json_encode(['database', 'sms']),
            'sent_at' => now()->subDays(5),
            'status' => 'failed',
            'read_at' => null,
        ]);

        // Pending notification
        Notification::create([
            'user_id' => $tenant2->id,
            'title_en' => 'System Maintenance',
            'title_ar' => 'صيانة النظام',
            'message_en' => 'Scheduled system maintenance on Saturday 2 AM - 4 AM.',
            'message_ar' => 'صيانة النظام المجدولة يوم السبت من 2 صباحًا حتى 4 صباحًا.',
            'type' => 'system',
            'priority' => 'low',
            'data' => json_encode(['maintenance_date' => '2025-01-18']),
            'channels' => json_encode(['database', 'email']),
            'sent_at' => null,
            'status' => 'pending',
            'read_at' => null,
        ]);
    }
}
