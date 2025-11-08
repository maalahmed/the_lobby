<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\Dashboard;
// use App\Livewire\Admin\Properties\PropertyIndex;
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

Route::middleware(['auth:sanctum'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/', Dashboard::class)->name('dashboard');
    
    // Properties Management
    // Route::get('/properties', PropertyIndex::class)->name('properties.index');
    
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
