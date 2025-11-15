<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Message;
use App\Models\User;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('email', 'admin@thelobby.com')->first();
        $landlord = User::where('email', 'landlord@thelobby.com')->first();
        $tenants = User::role('tenant')->limit(2)->get();

        if (!$admin || !$landlord || $tenants->count() < 2) {
            return; // Skip if users don't exist
        }

        $tenant1 = $tenants[0];
        $tenant2 = $tenants[1];

        // Thread 1: Tenant requesting AC maintenance
        $message1 = Message::create([
            'sender_id' => $tenant1->id,
            'receiver_id' => $landlord->id,
            'subject' => 'AC Maintenance Request',
            'body' => 'Hello, the AC in my unit is not cooling properly. Can you please send someone to check it?',
            'is_read' => true,
            'read_at' => now()->subDays(2),
            'parent_id' => null,
            'thread_id' => null,
            'attachments' => null,
            'is_archived' => false,
        ]);
        $message1->update(['thread_id' => $message1->id]);

        // Reply to Thread 1
        Message::create([
            'sender_id' => $landlord->id,
            'receiver_id' => $tenant1->id,
            'subject' => 'Re: AC Maintenance Request',
            'body' => 'I have scheduled a technician to visit tomorrow between 10 AM - 12 PM. Please make sure someone is available.',
            'is_read' => true,
            'read_at' => now()->subDays(1),
            'parent_id' => $message1->id,
            'thread_id' => $message1->id,
            'attachments' => null,
            'is_archived' => false,
        ]);

        // Thread 2: Landlord contacting tenant about lease renewal
        $message2 = Message::create([
            'sender_id' => $landlord->id,
            'receiver_id' => $tenant2->id,
            'subject' => 'Lease Renewal',
            'body' => 'Dear Tenant, your lease will expire in 2 months. Please let me know if you would like to renew.',
            'is_read' => false,
            'read_at' => null,
            'parent_id' => null,
            'thread_id' => null,
            'attachments' => null,
            'is_archived' => false,
        ]);
        $message2->update(['thread_id' => $message2->id]);

        // Thread 3: Admin announcement
        Message::create([
            'sender_id' => $admin->id,
            'receiver_id' => $landlord->id,
            'subject' => 'System Maintenance Notice',
            'body' => 'The system will undergo maintenance on Saturday from 2 AM to 4 AM. Services may be temporarily unavailable.',
            'is_read' => true,
            'read_at' => now()->subHours(5),
            'parent_id' => null,
            'thread_id' => null,
            'attachments' => null,
            'is_archived' => false,
        ]);

        // Thread 4: Tenant payment confirmation
        $message4 = Message::create([
            'sender_id' => $tenant1->id,
            'receiver_id' => $landlord->id,
            'subject' => 'Payment Confirmation',
            'body' => 'I have transferred this month\'s rent. Please find the attached bank receipt.',
            'is_read' => true,
            'read_at' => now()->subDays(3),
            'parent_id' => null,
            'thread_id' => null,
            'attachments' => json_encode(['receipt.pdf']),
            'is_archived' => false,
        ]);
        $message4->update(['thread_id' => $message4->id]);

        // Reply to Thread 4
        Message::create([
            'sender_id' => $landlord->id,
            'receiver_id' => $tenant1->id,
            'subject' => 'Re: Payment Confirmation',
            'body' => 'Thank you! Payment received and confirmed.',
            'is_read' => true,
            'read_at' => now()->subDays(3),
            'parent_id' => $message4->id,
            'thread_id' => $message4->id,
            'attachments' => null,
            'is_archived' => false,
        ]);
    }
}
