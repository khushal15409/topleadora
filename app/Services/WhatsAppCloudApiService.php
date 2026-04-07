<?php

namespace App\Services;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppCloudApiService
{
    public function isConfigured(): bool
    {
        return $this->token() !== '' && $this->phoneNumberId() !== '';
    }

    public function token(): string
    {
        return (string) (setting('whatsapp_token') ?? setting('integrations.whatsapp.api_token') ?? '');
    }

    public function phoneNumberId(): string
    {
        return (string) (setting('phone_number_id') ?? setting('integrations.whatsapp.phone_number_id') ?? '');
    }

    public function webhookVerifyToken(): string
    {
        return (string) (setting('webhook_verify_token') ?? setting('integrations.whatsapp.webhook_verify_token') ?? '');
    }

    /**
     * Real WhatsApp Cloud API call.
     *
     * @return array{ok: bool, message_id?: string, error?: string, response?: array<string, mixed>}
     */
    public function sendWhatsAppMessage(string $phone, string $message): array
    {
        $token = $this->token();
        $phoneNumberId = $this->phoneNumberId();

        if ($token === '' || $phoneNumberId === '') {
            return ['ok' => false, 'error' => 'WhatsApp credentials not configured.'];
        }

        $to = $this->normalizePhoneE164ish($phone);
        if ($to === '') {
            return ['ok' => false, 'error' => 'Invalid phone number.'];
        }

        $url = "https://graph.facebook.com/v18.0/{$phoneNumberId}/messages";

        try {
            $resp = Http::withToken($token)
                ->acceptJson()
                ->asJson()
                ->retry(2, 300, function ($exception) {
                    return $exception instanceof RequestException;
                })
                ->post($url, [
                    'messaging_product' => 'whatsapp',
                    'to' => $to,
                    'type' => 'text',
                    'text' => [
                        'body' => $message,
                    ],
                ])
                ->throw()
                ->json();

            $messageId = '';
            if (is_array($resp) && isset($resp['messages'][0]['id'])) {
                $messageId = (string) $resp['messages'][0]['id'];
            }

            return [
                'ok' => true,
                'message_id' => $messageId !== '' ? $messageId : null,
                'response' => is_array($resp) ? $resp : null,
            ];
        } catch (\Throwable $e) {
            Log::warning('WhatsApp send failed', [
                'to' => $phone,
                'error' => $e->getMessage(),
            ]);

            return ['ok' => false, 'error' => $e->getMessage()];
        }
    }

    private function normalizePhoneE164ish(string $phone): string
    {
        $p = preg_replace('/[^\d+]/', '', $phone) ?? '';
        $p = trim($p);

        // WhatsApp expects digits with country code. Keep '+' if provided; otherwise just digits.
        $p = ltrim($p);
        if ($p === '') {
            return '';
        }

        if (str_starts_with($p, '00')) {
            $p = '+'.substr($p, 2);
        }

        // If it starts with '+', keep; else return digits only.
        if (str_starts_with($p, '+')) {
            $digits = '+'.preg_replace('/[^\d]/', '', substr($p, 1));

            return $digits === '+' ? '' : $digits;
        }

        $digits = preg_replace('/[^\d]/', '', $p) ?? '';

        return $digits;
    }
}
