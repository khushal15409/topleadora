<?php

use App\Http\Controllers\Api\GatewayController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'api.balance'])->group(function () {
    // OTP endpoints should be stricter to reduce abuse.
    Route::post('/send-otp', [GatewayController::class, 'sendOtp'])->middleware('throttle:20,1');
    Route::post('/send-whatsapp', [GatewayController::class, 'sendWhatsapp'])->middleware('throttle:60,1');
});
