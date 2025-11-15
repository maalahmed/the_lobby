<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SystemSetting;

class SystemSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // General Settings
            ['key' => 'app_name', 'value' => 'The Lobby', 'group' => 'general', 'description' => 'Application Name', 'is_locked' => true],
            ['key' => 'currency', 'value' => 'AED', 'group' => 'general', 'description' => 'Default Currency', 'is_locked' => false],
            ['key' => 'timezone', 'value' => 'Asia/Dubai', 'group' => 'general', 'description' => 'Application Timezone', 'is_locked' => false],
            ['key' => 'language', 'value' => 'en', 'group' => 'general', 'description' => 'Default Language', 'is_locked' => false],
            ['key' => 'date_format', 'value' => 'd/m/Y', 'group' => 'general', 'description' => 'Date Display Format', 'is_locked' => false],
            ['key' => 'tax_rate', 'value' => '5', 'group' => 'general', 'description' => 'VAT Rate (%)', 'is_locked' => false],
            
            // Email Settings
            ['key' => 'smtp_host', 'value' => 'smtp.mailtrap.io', 'group' => 'email', 'description' => 'SMTP Server Host', 'is_locked' => false],
            ['key' => 'smtp_port', 'value' => '2525', 'group' => 'email', 'description' => 'SMTP Server Port', 'is_locked' => false],
            ['key' => 'smtp_username', 'value' => '', 'group' => 'email', 'description' => 'SMTP Username', 'is_locked' => false],
            ['key' => 'smtp_password', 'value' => '', 'group' => 'email', 'description' => 'SMTP Password', 'is_locked' => false],
            ['key' => 'from_email', 'value' => 'noreply@thelobby.ae', 'group' => 'email', 'description' => 'From Email Address', 'is_locked' => false],
            ['key' => 'from_name', 'value' => 'The Lobby', 'group' => 'email', 'description' => 'From Name', 'is_locked' => false],
            
            // Payment Settings
            ['key' => 'accepted_payment_methods', 'value' => 'bank_transfer,credit_card,cash,cheque', 'group' => 'payment', 'description' => 'Accepted Payment Methods', 'is_locked' => false],
            ['key' => 'late_payment_fee', 'value' => '200', 'group' => 'payment', 'description' => 'Late Payment Fee (AED)', 'is_locked' => false],
            ['key' => 'grace_period_days', 'value' => '5', 'group' => 'payment', 'description' => 'Grace Period (Days)', 'is_locked' => false],
            ['key' => 'security_deposit_months', 'value' => '1', 'group' => 'payment', 'description' => 'Security Deposit (Months)', 'is_locked' => false],
            
            // Notification Settings
            ['key' => 'notifications_enabled', 'value' => 'true', 'group' => 'notifications', 'description' => 'Enable Notifications', 'is_locked' => false],
            ['key' => 'email_notifications', 'value' => 'true', 'group' => 'notifications', 'description' => 'Enable Email Notifications', 'is_locked' => false],
            ['key' => 'sms_notifications', 'value' => 'false', 'group' => 'notifications', 'description' => 'Enable SMS Notifications', 'is_locked' => false],
            ['key' => 'push_notifications', 'value' => 'true', 'group' => 'notifications', 'description' => 'Enable Push Notifications', 'is_locked' => false],
            ['key' => 'notification_retention_days', 'value' => '90', 'group' => 'notifications', 'description' => 'Notification Retention (Days)', 'is_locked' => false],
        ];

        foreach ($settings as $setting) {
            SystemSetting::create($setting);
        }
    }
}
