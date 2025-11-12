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
// use App\Livewire\Admin\Users\UserIndex;
// use App\Livewire\Admin\Contracts\ContractIndex;
// use App\Livewire\Admin\Maintenance\MaintenanceIndex;
// use App\Livewire\Admin\Reports\ReportIndex;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

// TODO: Add proper authentication middleware after implementing login
Route::prefix('admin')->name('admin.')->group(function () {
    
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
    
    // Users Management
    // Route::get('/users', UserIndex::class)->name('users.index');
    
    // Contracts Management
    // Route::get('/contracts', ContractIndex::class)->name('contracts.index');
    
    // Invoices Management
    // Route::get('/invoices', InvoiceIndex::class)->name('invoices.index');
    
    // Maintenance Management
    // Route::get('/maintenance', MaintenanceIndex::class)->name('maintenance.index');
    
    // Reports
    // Route::get('/reports', ReportIndex::class)->name('reports');
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
