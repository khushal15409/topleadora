<?php

namespace App\Services;

class OtpService
{
    /**
     * Send OTP logic.
     * In a real app, this would integrate with a real SMS provider like Twilio/MessageBird.
     */
    public function send(string $phone, string $message): array
    {
        // Mock successful sending
        // Validation of length or standard could throw an exception instead
        return [
            'success' => true,
            'response' => [
                'provider_id' => 'mock_otp_' . uniqid(),
                'status' => 'delivered',
            ],
        ];
    }
}
