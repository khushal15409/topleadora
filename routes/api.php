<?php

use App\Http\Controllers\Api\GatewayController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'api.balance'])->group(function () {
    Route::post('/send-otp', [GatewayController::class, 'sendOtp']);
    Route::post('/send-whatsapp', [GatewayController::class, 'sendWhatsapp']);
});
