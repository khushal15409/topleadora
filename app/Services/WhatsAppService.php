<?php

namespace App\Services;

class WhatsAppService
{
    /**
     * Send WhatsApp message API integration.
     * In a real app, this integrates with Meta Cloud API or similar Official provider.
     */
    public function send(string $phone, string $message): array
    {
        // Mock successful sending
        return [
            'success' => true,
            'response' => [
                'provider_id' => 'mock_wa_' . uniqid(),
                'status' => 'delivered',
            ],
        ];
    }
}
