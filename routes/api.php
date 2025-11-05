<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PropertyController;
use App\Http\Controllers\Api\PropertyUnitController;
use App\Http\Controllers\Api\TenantController;
use App\Http\Controllers\Api\LeaseContractController;
use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\MaintenanceRequestController;
use App\Http\Controllers\Api\MaintenanceJobController;
use App\Http\Controllers\Api\ServiceProviderController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\MessageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes
Route::prefix('v1')->group(function () {
    // Authentication
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

// Protected routes
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    // Authentication
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/logout-all', [AuthController::class, 'logoutAll']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);
    Route::put('/change-password', [AuthController::class, 'changePassword']);

    // Properties
    Route::apiResource('properties', PropertyController::class);
    Route::get('properties/{property}/units', [PropertyController::class, 'units']);
    Route::get('properties/{property}/contracts', [PropertyController::class, 'contracts']);
    Route::get('properties/{property}/maintenance', [PropertyController::class, 'maintenanceRequests']);

    // Property Units
    Route::apiResource('units', PropertyUnitController::class);

    // Tenants
    Route::apiResource('tenants', TenantController::class);

    // Lease Contracts
    Route::apiResource('contracts', LeaseContractController::class);
    Route::post('contracts/{contract}/sign', [LeaseContractController::class, 'sign']);

    // Invoices
    Route::apiResource('invoices', InvoiceController::class);
    Route::post('invoices/{invoice}/send', [InvoiceController::class, 'send']);
    Route::get('invoices/{invoice}/download', [InvoiceController::class, 'download']);

    // Payments
    Route::apiResource('payments', PaymentController::class);
    Route::post('payments/{payment}/verify', [PaymentController::class, 'verify']);

    // Maintenance Requests
    Route::apiResource('maintenance-requests', MaintenanceRequestController::class);
    Route::post('maintenance-requests/{request}/assign', [MaintenanceRequestController::class, 'assign']);
    Route::post('maintenance-requests/{request}/complete', [MaintenanceRequestController::class, 'complete']);

    // Maintenance Jobs
    Route::apiResource('maintenance-jobs', MaintenanceJobController::class);
    Route::post('maintenance-jobs/{job}/accept', [MaintenanceJobController::class, 'accept']);
    Route::post('maintenance-jobs/{job}/reject', [MaintenanceJobController::class, 'reject']);
    Route::post('maintenance-jobs/{job}/start', [MaintenanceJobController::class, 'start']);
    Route::post('maintenance-jobs/{job}/complete', [MaintenanceJobController::class, 'complete']);

    // Service Providers
    Route::apiResource('service-providers', ServiceProviderController::class);

    // Notifications
    Route::get('notifications', [NotificationController::class, 'index']);
    Route::get('notifications/unread', [NotificationController::class, 'unread']);
    Route::post('notifications/{notification}/read', [NotificationController::class, 'markAsRead']);
    Route::post('notifications/read-all', [NotificationController::class, 'markAllAsRead']);

    // Messages
    Route::apiResource('messages', MessageController::class);
    Route::get('messages/thread/{threadId}', [MessageController::class, 'thread']);
    Route::post('messages/{message}/read', [MessageController::class, 'markAsRead']);
});
