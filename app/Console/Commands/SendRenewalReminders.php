<?php

namespace App\Console\Commands;

use App\Models\LeaseContract;
use App\Notifications\LeaseRenewalReminderNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendRenewalReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'renewals:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send lease renewal reminder notifications to landlords';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for expiring leases...');

        $reminderDays = [60, 30, 15]; // Days before expiration to send reminders
        $sentCount = 0;

        foreach ($reminderDays as $days) {
            $targetDate = Carbon::today()->addDays($days);
            
            // Find leases expiring on the target date that haven't been reminded for this period
            $leases = LeaseContract::with(['unit.property', 'tenant.user', 'landlord'])
                ->where('end_date', $targetDate->toDateString())
                ->where('status', 'active')
                ->whereDoesntHave('renewalOffers', function ($query) {
                    $query->whereIn('status', ['accepted', 'sent']);
                })
                ->get();

            foreach ($leases as $lease) {
                if ($lease->landlord) {
                    $lease->landlord->notify(new LeaseRenewalReminderNotification($lease, $days));
                    $sentCount++;
                    
                    $this->info("Sent {$days}-day reminder for lease #{$lease->id} to {$lease->landlord->name}");
                }
            }
        }

        $this->info("Total reminders sent: {$sentCount}");
        
        return Command::SUCCESS;
    }
}
