<?php

namespace App\Http\Controllers\Webhooks;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Organization;
use App\Models\WhatsAppInboundMessage;
use App\Services\WhatsAppCloudApiService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class WhatsAppWebhookController extends Controller
{
    /**
     * GET verification endpoint.
     */
    public function verify(Request $request, WhatsAppCloudApiService $wa): Response
    {
        $mode = (string) $request->query('hub_mode', $request->query('hub.mode', ''));
        $token = (string) $request->query('hub_verify_token', $request->query('hub.verify_token', ''));
        $challenge = (string) $request->query('hub_challenge', $request->query('hub.challenge', ''));

        if ($mode !== 'subscribe') {
            return response('Invalid mode', 400);
        }

        if ($token === '' || $token !== $wa->webhookVerifyToken()) {
            return response('Invalid verify token', 403);
        }

        return response($challenge, 200);
    }

    /**
     * POST webhook receiver.
     */
    public function receive(Request $request): Response
    {
        /** @var array<string, mixed> $payload */
        $payload = (array) $request->all();

        try {
            $messages = $payload['entry'][0]['changes'][0]['value']['messages'] ?? [];
            if (! is_array($messages) || empty($messages)) {
                return response('OK', 200);
            }

            $inboundOrgId = (int) (setting('integrations.whatsapp.inbound_organization_id') ?? 0);
            $org = $inboundOrgId > 0
                ? Organization::query()->whereKey($inboundOrgId)->first()
                : null;

            foreach ($messages as $msg) {
                if (! is_array($msg)) {
                    continue;
                }

                $from = (string) ($msg['from'] ?? '');
                $waMsgId = (string) ($msg['id'] ?? '');
                $body = (string) (($msg['text']['body'] ?? '') ?: '');

                if ($waMsgId !== '') {
                    WhatsAppInboundMessage::query()->updateOrCreate(
                        ['wa_message_id' => $waMsgId],
                        [
                            'organization_id' => $org?->id,
                            'from_phone' => $from !== '' ? $from : null,
                            'body' => $body !== '' ? $body : null,
                            'payload' => $payload,
                            'received_at' => now(),
                        ]
                    );
                }

                // Auto-capture lead only when an organization is configured (prevents cross-tenant ambiguity).
                if ($org && $from !== '') {
                    $lead = Lead::query()
                        ->where('organization_id', $org->id)
                        ->where('phone', 'like', '%'.$from.'%')
                        ->first();

                    if (! $lead) {
                        Lead::query()->create([
                            'organization_id' => $org->id,
                            'assigned_to' => null,
                            'name' => 'WhatsApp Lead '.$from,
                            'phone' => $from,
                            'status' => Lead::STATUS_NEW,
                            'source' => Lead::SOURCE_WHATSAPP,
                            'notes' => $body !== '' ? $body : null,
                        ]);
                    } elseif ($body !== '') {
                        $lead->forceFill([
                            'notes' => trim(($lead->notes ? $lead->notes."\n\n" : '').'Inbound WA: '.$body),
                        ])->save();
                    }
                }
            }
        } catch (\Throwable $e) {
            Log::error('WhatsApp webhook processing failed', [
                'error' => $e->getMessage(),
            ]);
        }

        return response('OK', 200);
    }
}

