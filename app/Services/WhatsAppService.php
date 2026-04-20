<?php

namespace App\Services;

class WhatsAppService
{
    public function __construct(protected WhatsAppCloudApiService $cloudApi)
    {
    }

    /**
     * Send WhatsApp using Meta Cloud API when Super Admin integrations are configured; otherwise simulate success for local/dev.
     */
    public function send(string $phone, string $message): array
    {
        if (!$this->cloudApi->isConfigured()) {
            return [
                'success' => true,
                'response' => [
                    'provider_id' => 'mock_wa_' . uniqid(),
                    'status' => 'delivered',
                    'mode' => 'simulation',
                ],
            ];
        }

        $res = $this->cloudApi->sendWhatsAppMessage($phone, $message);
        if (!($res['ok'] ?? false)) {
            return [
                'success' => false,
                'response' => [
                    'error' => (string) ($res['error'] ?? 'send_failed'),
                ],
            ];
        }

        return [
            'success' => true,
            'response' => [
                'provider_id' => (string) ($res['message_id'] ?? ''),
                'status' => 'delivered',
                'raw' => $res['response'] ?? null,
            ],
        ];
    }
}
