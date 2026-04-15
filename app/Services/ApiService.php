<?php

namespace App\Services;

use App\Models\ApiUsageLog;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class ApiService
{
    public function sendOtp(User $user, string $phone): array
    {
        $organization = $user->organization;

        // Mocking the OTP sending logic
        $otp = rand(1000, 9999);
        $message = "Your OTP is: $otp";

        // In a real scenario, call your WhatsApp/SMS provider API here
        $success = true; // Assume success for now

        $log = ApiUsageLog::create([
            'user_id' => $user->id,
            'organization_id' => $organization->id,
            'type' => 'otp',
            'phone' => $phone,
            'message' => $message,
            'status' => $success ? 'success' : 'failed',
            'response' => json_encode(['mock_response' => 'Sent via Mock Provider']),
        ]);

        if ($success) {
            $this->deductBalance($organization, 1.00); // Cost of 1 credit
        }

        return [
            'success' => $success,
            'message_id' => $log->id,
            'status' => 'delivered'
        ];
    }

    public function sendWhatsApp(User $user, string $phone, string $message): array
    {
        $organization = $user->organization;

        // Mocking the WhatsApp sending logic
        $success = true;

        $log = ApiUsageLog::create([
            'user_id' => $user->id,
            'organization_id' => $organization->id,
            'type' => 'whatsapp',
            'phone' => $phone,
            'message' => $message,
            'status' => $success ? 'success' : 'failed',
            'response' => json_encode(['mock_response' => 'Sent via Mock WhatsApp Provider']),
        ]);

        if ($success) {
            $this->deductBalance($organization, 2.00); // Cost of 2 credits for WhatsApp
        }

        return [
            'success' => $success,
            'message_id' => $log->id,
            'status' => 'delivered'
        ];
    }

    protected function deductBalance($organization, float $amount): void
    {
        $organization->decrement('wallet_balance', $amount);
    }
}
