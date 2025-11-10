<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Properties\Index as PropertiesIndex;
use App\Livewire\Admin\Properties\Create as PropertiesCreate;
// use App\Livewire\Admin\Users\UserIndex;
// use App\Livewire\Admin\Contracts\ContractIndex;
// use App\Livewire\Admin\Invoices\InvoiceIndex;
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
        // TODO: Add show, edit routes when components are ready
        // Route::get('/{property}', Show::class)->name('show');
        // Route::get('/{property}/edit', Edit::class)->name('edit');
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
