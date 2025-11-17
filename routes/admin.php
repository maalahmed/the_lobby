<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Properties\Index as PropertiesIndex;
use App\Livewire\Admin\Properties\Create as PropertiesCreate;
use App\Livewire\Admin\Properties\Show as PropertiesShow;
use App\Livewire\Admin\Properties\Edit as PropertiesEdit;
use App\Livewire\Admin\Units\Index as UnitsIndex;
use App\Livewire\Admin\Units\Create as UnitsCreate;
use App\Livewire\Admin\Units\Show as UnitsShow;
use App\Livewire\Admin\Units\Edit as UnitsEdit;
use App\Livewire\Admin\Tenants\Index as TenantsIndex;
use App\Livewire\Admin\Tenants\Create as TenantsCreate;
use App\Livewire\Admin\Tenants\Show as TenantsShow;
use App\Livewire\Admin\Tenants\Edit as TenantsEdit;
use App\Livewire\Admin\LeaseContracts\Index as LeaseContractsIndex;
use App\Livewire\Admin\LeaseContracts\Create as LeaseContractsCreate;
use App\Livewire\Admin\LeaseContracts\Show as LeaseContractsShow;
use App\Livewire\Admin\LeaseContracts\Edit as LeaseContractsEdit;
use App\Livewire\Admin\Invoices\Index as InvoicesIndex;
use App\Livewire\Admin\Invoices\Create as InvoicesCreate;
use App\Livewire\Admin\Invoices\Show as InvoicesShow;
use App\Livewire\Admin\Invoices\Edit as InvoicesEdit;
use App\Livewire\Admin\Payments\Index as PaymentsIndex;
use App\Livewire\Admin\Payments\Create as PaymentsCreate;
use App\Livewire\Admin\Payments\Show as PaymentsShow;
use App\Livewire\Admin\Payments\Edit as PaymentsEdit;
use App\Livewire\Admin\MaintenanceRequests\Index as MaintenanceRequestsIndex;
use App\Livewire\Admin\MaintenanceRequests\Create as MaintenanceRequestsCreate;
use App\Livewire\Admin\MaintenanceRequests\Show as MaintenanceRequestsShow;
use App\Livewire\Admin\MaintenanceRequests\Edit as MaintenanceRequestsEdit;
use App\Livewire\Admin\MaintenanceJobs\Index as MaintenanceJobsIndex;
use App\Livewire\Admin\MaintenanceJobs\Create as MaintenanceJobsCreate;
use App\Livewire\Admin\MaintenanceJobs\Show as MaintenanceJobsShow;
use App\Livewire\Admin\MaintenanceJobs\Edit as MaintenanceJobsEdit;
use App\Livewire\Admin\ServiceProviders\Index as ServiceProvidersIndex;
use App\Livewire\Admin\ServiceProviders\Create as ServiceProvidersCreate;
use App\Livewire\Admin\ServiceProviders\Show as ServiceProvidersShow;
use App\Livewire\Admin\ServiceProviders\Edit as ServiceProvidersEdit;
use App\Livewire\Admin\UserProfiles\Index as UserProfilesIndex;
use App\Livewire\Admin\UserProfiles\Create as UserProfilesCreate;
use App\Livewire\Admin\UserProfiles\Show as UserProfilesShow;
use App\Livewire\Admin\UserProfiles\Edit as UserProfilesEdit;
use App\Livewire\Admin\SystemSettings\Index as SystemSettingsIndex;
use App\Livewire\Admin\SystemSettings\Create as SystemSettingsCreate;
use App\Livewire\Admin\SystemSettings\Show as SystemSettingsShow;
use App\Livewire\Admin\SystemSettings\Edit as SystemSettingsEdit;
use App\Livewire\Admin\AuditLogs\Index as AuditLogsIndex;
use App\Livewire\Admin\AuditLogs\Show as AuditLogsShow;
use App\Livewire\Admin\Messages\Index as MessagesIndex;
use App\Livewire\Admin\Messages\Create as MessagesCreate;
use App\Livewire\Admin\Messages\Show as MessagesShow;
use App\Livewire\Admin\Messages\Edit as MessagesEdit;
use App\Livewire\Admin\Notifications\Index as NotificationsIndex;
use App\Livewire\Admin\Notifications\Create as NotificationsCreate;
use App\Livewire\Admin\Notifications\Show as NotificationsShow;
use App\Livewire\Admin\Notifications\Edit as NotificationsEdit;
use App\Livewire\Admin\Vacancies\Dashboard as VacanciesDashboard;
use App\Livewire\Admin\Vacancies\Calendar as VacanciesCalendar;
use App\Livewire\Admin\LeaseRenewals\Index as LeaseRenewalsIndex;
use App\Livewire\Admin\LeaseRenewals\Create as LeaseRenewalsCreate;
use App\Livewire\Admin\Users\Index as UsersIndex;
use App\Livewire\Admin\Users\Create as UsersCreate;
use App\Livewire\Admin\Users\Edit as UsersEdit;
// use App\Livewire\Admin\Users\UserIndex;
// use App\Livewire\Admin\Contracts\ContractIndex;
// use App\Livewire\Admin\Maintenance\MaintenanceIndex;
use App\Livewire\Admin\Reports\Index as ReportsIndex;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/', Dashboard::class)->name('dashboard');

    // Properties Management
    Route::prefix('properties')->name('properties.')->group(function () {
        Route::get('/', PropertiesIndex::class)->name('index');
        Route::get('/create', PropertiesCreate::class)->name('create');
        Route::get('/{property}', PropertiesShow::class)->name('show');
        Route::get('/{property}/edit', PropertiesEdit::class)->name('edit');
    });

    // Units Management
    Route::prefix('units')->name('units.')->group(function () {
        Route::get('/', UnitsIndex::class)->name('index');
        Route::get('/create', UnitsCreate::class)->name('create');
        Route::get('/{unit}', UnitsShow::class)->name('show');
        Route::get('/{unit}/edit', UnitsEdit::class)->name('edit');
    });

    // Tenants Management
    Route::prefix('tenants')->name('tenants.')->group(function () {
        Route::get('/', TenantsIndex::class)->name('index');
        Route::get('/create', TenantsCreate::class)->name('create');
        Route::get('/{tenant}', TenantsShow::class)->name('show');
        Route::get('/{tenant}/edit', TenantsEdit::class)->name('edit');
    });

    // Lease Contracts Management
    Route::prefix('lease-contracts')->name('lease-contracts.')->group(function () {
        Route::get('/', LeaseContractsIndex::class)->name('index');
        Route::get('/create', LeaseContractsCreate::class)->name('create');
        Route::get('/{contract}', LeaseContractsShow::class)->name('show');
        Route::get('/{contract}/edit', LeaseContractsEdit::class)->name('edit');
    });

    // Invoices Management
    Route::prefix('invoices')->name('invoices.')->group(function () {
        Route::get('/', InvoicesIndex::class)->name('index');
        Route::get('/create', InvoicesCreate::class)->name('create');
        Route::get('/{invoice}', InvoicesShow::class)->name('show');
        Route::get('/{invoice}/edit', InvoicesEdit::class)->name('edit');
    });

    // Payments Management
    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('/', PaymentsIndex::class)->name('index');
        Route::get('/create', PaymentsCreate::class)->name('create');
        Route::get('/{payment}', PaymentsShow::class)->name('show');
        Route::get('/{payment}/edit', PaymentsEdit::class)->name('edit');
    });

    // Maintenance Requests Management
    Route::prefix('maintenance-requests')->name('maintenance-requests.')->group(function () {
        Route::get('/', MaintenanceRequestsIndex::class)->name('index');
        Route::get('/create', MaintenanceRequestsCreate::class)->name('create');
        Route::get('/{request}', MaintenanceRequestsShow::class)->name('show');
        Route::get('/{request}/edit', MaintenanceRequestsEdit::class)->name('edit');
    });

    // Maintenance Jobs Management
    Route::prefix('maintenance-jobs')->name('maintenance-jobs.')->group(function () {
        Route::get('/', MaintenanceJobsIndex::class)->name('index');
        Route::get('/create', MaintenanceJobsCreate::class)->name('create');
        Route::get('/{job}', MaintenanceJobsShow::class)->name('show');
        Route::get('/{job}/edit', MaintenanceJobsEdit::class)->name('edit');
    });

    // Service Providers Management
    Route::prefix('service-providers')->name('service-providers.')->group(function () {
        Route::get('/', ServiceProvidersIndex::class)->name('index');
        Route::get('/create', ServiceProvidersCreate::class)->name('create');
        Route::get('/{provider}', ServiceProvidersShow::class)->name('show');
        Route::get('/{provider}/edit', ServiceProvidersEdit::class)->name('edit');
    });

    // User Profiles Management
    Route::prefix('user-profiles')->name('user-profiles.')->group(function () {
        Route::get('/', UserProfilesIndex::class)->name('index');
        Route::get('/create', UserProfilesCreate::class)->name('create');
        Route::get('/{profile}', UserProfilesShow::class)->name('show');
        Route::get('/{profile}/edit', UserProfilesEdit::class)->name('edit');
    });

    // System Settings Management
    Route::prefix('system-settings')->name('system-settings.')->group(function () {
        Route::get('/', SystemSettingsIndex::class)->name('index');
        Route::get('/create', SystemSettingsCreate::class)->name('create');
        Route::get('/{setting}/edit', SystemSettingsEdit::class)->name('edit');
        Route::get('/{setting}', SystemSettingsShow::class)->name('show');
    });

    // Audit Logs (Read-Only)
    Route::prefix('audit-logs')->name('audit-logs.')->group(function () {
        Route::get('/', AuditLogsIndex::class)->name('index');
        Route::get('/{log}', AuditLogsShow::class)->name('show');
    });

    // Messages Management
    Route::prefix('messages')->name('messages.')->group(function () {
        Route::get('/', MessagesIndex::class)->name('index');
        Route::get('/create', MessagesCreate::class)->name('create');
        Route::get('/{message}', MessagesShow::class)->name('show');
        Route::get('/{message}/edit', MessagesEdit::class)->name('edit');
    });

    // Notifications Management
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', NotificationsIndex::class)->name('index');
        Route::get('/create', NotificationsCreate::class)->name('create');
        Route::get('/{notification}', NotificationsShow::class)->name('show');
        Route::get('/{notification}/edit', NotificationsEdit::class)->name('edit');
    });

    // Users Management
    // Route::get('/users', UserIndex::class)->name('users.index');

    // Contracts Management
    // Route::get('/contracts', ContractIndex::class)->name('contracts.index');

    // Invoices Management
    // Route::get('/invoices', InvoiceIndex::class)->name('invoices.index');

    // Maintenance Management
    // Route::get('/maintenance', MaintenanceIndex::class)->name('maintenance.index');

    // Vacancy Management
    Route::get('/vacancies', VacanciesDashboard::class)->name('vacancies.dashboard');
    Route::get('/vacancies/calendar', VacanciesCalendar::class)->name('vacancies.calendar');

    // Lease Renewals
    Route::get('/lease-renewals', LeaseRenewalsIndex::class)->name('lease-renewals.index');
    Route::get('/lease-renewals/create/{leaseId}', LeaseRenewalsCreate::class)->name('lease-renewals.create');

    // Users Management
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', UsersIndex::class)->name('index');
        Route::get('/create', UsersCreate::class)->name('create');
        Route::get('/{userId}/edit', UsersEdit::class)->name('edit');
    });

    // Reports
    Route::get('/reports', ReportsIndex::class)->name('reports');

    // Roles & Permissions Management
    Route::middleware(['role:admin'])->prefix('roles')->name('roles.')->group(function () {
        Route::get('/', \App\Livewire\Admin\Roles\Index::class)->name('index');
        Route::get('/create', \App\Livewire\Admin\Roles\Create::class)->name('create');
        Route::get('/{role}/edit', \App\Livewire\Admin\Roles\Edit::class)->name('edit');
    });
});

// Language Switcher
Route::get('/locale/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'ar'])) {
        session(['locale' => $locale]);
        app()->setLocale($locale);
    }
    return redirect()->back();
})->name('locale.switch');
# Test deployment - Mon Nov 10 14:08:09 +03 2025
