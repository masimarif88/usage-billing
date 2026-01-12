<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\UsageController;
use App\Http\Controllers\Api\V1\UsageSummaryController;
use App\Http\Controllers\Api\V1\InvoiceController;
use App\Http\Controllers\Api\V1\StripeWebhookController;
use App\Http\Controllers\Api\V1\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| All API routes are versioned under /api/v1
| Authenticated routes use sanctum middleware.
|
*/
Route::get('/login', function () {
    return response()->json([
        'message' => 'Unauthenticated'
    ], 401);
})->name('login');


// Versioned API Prefix
Route::prefix('v1')->group(function () {

    Route::post('/login', [AuthController::class, 'login'])
        ->name('auth.login');

    // Stripe webhook (no auth required)
    Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle'])
        ->name('stripe.webhook');

    // Authenticated routes (API token or Sanctum)
    Route::middleware('auth:sanctum')->group(function () {

        // Usage
        Route::post('/usage', [UsageController::class, 'store'])
            ->name('usage.record');

        // Usage Summary
        Route::get('/usage-summary', [UsageSummaryController::class, 'show'])
            ->name('usage.summary');

        // Invoices (optional endpoints)
        Route::get('/invoices', [InvoiceController::class, 'index'])
            ->name('invoices.list');

        Route::get('/invoices/{invoice}', [InvoiceController::class, 'show'])
            ->name('invoices.show');

        Route::post('/invoices/{invoice}/pay', [InvoiceController::class, 'pay'])
            ->name('invoices.pay');

        Route::post('/invoices/generate', [InvoiceController::class, 'generate'])
            ->name('invoices.generate');
    });

});
