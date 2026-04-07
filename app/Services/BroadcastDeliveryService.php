<?php

namespace App\Services;

use App\Models\Lead;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

/**
 * WhatsApp delivery integration point. Replace stub with your API client (Cloud API, BSP, etc.).
 */
class BroadcastDeliveryService
{
    /**
     * @param  Collection<int, Lead>  $leads
     */
    public function sendBulk(Collection $leads, string $message, WhatsAppCloudApiService $wa): array
    {
        if ($leads->isEmpty()) {
            return ['sent' => 0, 'failed' => 0, 'last_error' => null];
        }

        if (! $wa->isConfigured()) {
            return ['sent' => 0, 'failed' => $leads->count(), 'last_error' => 'WhatsApp Cloud API not configured.'];
        }

        $sent = 0;
        $failed = 0;
        $lastError = null;

        foreach ($leads as $lead) {
            $phone = (string) ($lead->phone ?? '');
            if ($phone === '') {
                $failed++;
                $lastError = 'Missing phone number.';

                continue;
            }

            $res = $wa->sendWhatsAppMessage($phone, $message);
            if (! ($res['ok'] ?? false)) {
                $failed++;
                $lastError = (string) ($res['error'] ?? 'Send failed');

                continue;
            }

            $sent++;
        }

        Log::info('BroadcastDeliveryService: WhatsApp send', [
            'recipients' => $leads->count(),
            'sent' => $sent,
            'failed' => $failed,
        ]);

        return ['sent' => $sent, 'failed' => $failed, 'last_error' => $lastError];
    }
}
