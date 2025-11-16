<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
require __DIR__.'/admin.php';

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('tenant')->name('tenant.')->group(function () {
    use App\Livewire\Tenant\Renewals\Index as TenantRenewalsIndex;
    use App\Livewire\Tenant\Renewals\Show as TenantRenewalsShow;
    
    Route::get('/renewals', TenantRenewalsIndex::class)->name('renewals.index');
    Route::get('/renewals/{offerId}', TenantRenewalsShow::class)->name('renewals.show');
});
