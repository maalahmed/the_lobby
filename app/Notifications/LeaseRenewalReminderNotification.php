<?php

namespace App\Notifications;

use App\Models\LeaseContract;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LeaseRenewalReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $lease;
    public $daysRemaining;

    /**
     * Create a new notification instance.
     */
    public function __construct(LeaseContract $lease, int $daysRemaining)
    {
        $this->lease = $lease;
        $this->daysRemaining = $daysRemaining;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $propertyName = $this->lease->unit->property->name ?? 'your property';
        $unitNumber = $this->lease->unit->unit_number ?? '';

        return (new MailMessage)
            ->subject("Lease Expiring in {$this->daysRemaining} Days - Action Required")
            ->greeting("Hello {$notifiable->name},")
            ->line("This is a reminder that the lease agreement for {$propertyName} - Unit {$unitNumber} is expiring soon.")
            ->line("**Lease Expiration Date:** {$this->lease->end_date->format('F j, Y')}")
            ->line("**Days Remaining:** {$this->daysRemaining} days")
            ->line("**Current Rent:** AED " . number_format($this->lease->rent_amount, 2) . " ({$this->lease->rent_frequency})")
            ->action('Review Lease Details', route('admin.lease-contracts.show', $this->lease->id))
            ->line('Please take necessary action to either:')
            ->line('• Create a renewal offer for the tenant')
            ->line('• Prepare for lease termination')
            ->line('• Start searching for a new tenant')
            ->line('Thank you for using The Lobby property management system.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $propertyName = $this->lease->unit->property->name ?? 'Property';
        $unitNumber = $this->lease->unit->unit_number ?? '';

        return [
            'type' => 'lease_renewal_reminder',
            'title' => "Lease Expiring in {$this->daysRemaining} Days",
            'message' => "Lease for {$propertyName} - Unit {$unitNumber} expires on {$this->lease->end_date->format('F j, Y')}",
            'data' => [
                'lease_id' => $this->lease->id,
                'property_id' => $this->lease->property_id,
                'unit_id' => $this->lease->unit_id,
                'tenant_id' => $this->lease->tenant_id,
                'days_remaining' => $this->daysRemaining,
                'expiration_date' => $this->lease->end_date->toDateString(),
                'current_rent' => $this->lease->rent_amount,
            ],
            'notifiable_type' => 'App\\Models\\LeaseContract',
            'notifiable_id' => $this->lease->id,
            'priority' => $this->daysRemaining <= 15 ? 'high' : 'normal',
            'is_actionable' => true,
            'action_url' => route('admin.lease-renewals.create', $this->lease->id),
        ];
    }
}
