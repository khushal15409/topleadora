<?php

namespace App\Services;

use App\Models\ApiUsageLog;
use App\Models\User;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Shared OTP / WhatsApp send pipeline (wallet reserve, provider call, logs).
 * Used by Sanctum API routes and the API Client dashboard test tool.
 */
class ApiGatewaySendService
{
    protected const OTP_COST_INR = 0.50;

    protected const WA_COST_INR = 1.00;

    public function __construct(
        protected OtpService $otpService,
        protected WhatsAppService $whatsappService,
    ) {
    }

    /**
     * @return array{status: int, body: array<string, mixed>}
     */
    public function execute(string $type, User $user, string $phone, string $message): array
    {
        $organization = $user->organization;
        if (!$organization) {
            return ['status' => 403, 'body' => ['error' => 'No organization found.']];
        }

        $cost = $type === 'otp' ? self::OTP_COST_INR : self::WA_COST_INR;
        $reservationRef = (string) Str::uuid();

        try {
            $reserved = DB::transaction(function () use ($organization, $cost, $type, $phone, $reservationRef) {
                $org = $organization->newQuery()->whereKey($organization->id)->lockForUpdate()->first();
                if (!$org) {
                    return ['ok' => false, 'status' => 403, 'message' => 'No organization found.'];
                }
                if (!$org->api_access_enabled) {
                    return ['ok' => false, 'status' => 403, 'message' => 'API Access is disabled for this organization.'];
                }
                if ((float) $org->wallet_balance < (float) $cost) {
                    return ['ok' => false, 'status' => 402, 'message' => 'Insufficient wallet balance.'];
                }

                $org->decrement('wallet_balance', $cost);

                $maskedPhone = $this->maskPhone($phone);
                WalletTransaction::create([
                    'organization_id' => $org->id,
                    'amount' => $cost,
                    'type' => 'debit',
                    'source' => 'api_usage',
                    'reference_id' => $reservationRef,
                    'description' => strtoupper($type) . ' usage (reserved) for ' . $maskedPhone,
                    'status' => 'pending',
                ]);

                return ['ok' => true];
            }, 3);

            if (($reserved['ok'] ?? false) !== true) {
                return [
                    'status' => (int) ($reserved['status'] ?? 402),
                    'body' => ['error' => (string) ($reserved['message'] ?? 'Unable to reserve balance.')],
                ];
            }
        } catch (\Throwable) {
            return ['status' => 500, 'body' => ['error' => 'Unable to reserve wallet balance. Please retry.']];
        }

        try {
            $serviceResponse = $type === 'otp'
                ? $this->otpService->send($phone, $message)
                : $this->whatsappService->send($phone, $message);

            $status = $serviceResponse['success'] ? 'success' : 'failed';

            DB::transaction(function () use ($user, $organization, $type, $phone, $message, $cost, $status, $serviceResponse, $reservationRef) {
                $maskedPhone = $this->maskPhone($phone);
                $safeMsg = $this->safeMessageForLog($message);
                /** @var WalletTransaction|null $trx */
                $trx = WalletTransaction::query()
                    ->where('organization_id', $organization->id)
                    ->where('reference_id', $reservationRef)
                    ->where('type', 'debit')
                    ->lockForUpdate()
                    ->first();

                if ($trx) {
                    if ($status === 'success') {
                        $trx->update([
                            'status' => 'success',
                            'description' => strtoupper($type) . ' usage deduction for ' . $maskedPhone,
                        ]);
                    } else {
                        $organization->increment('wallet_balance', $cost);
                        $trx->update([
                            'status' => 'failed',
                            'description' => strtoupper($type) . ' failed — reservation refunded for ' . $maskedPhone,
                        ]);
                    }
                } else {
                    if ($status !== 'success') {
                        $organization->increment('wallet_balance', $cost);
                    }
                }

                ApiUsageLog::create([
                    'user_id' => $user->id,
                    'organization_id' => $organization->id,
                    'type' => $type,
                    'phone' => $maskedPhone,
                    'message' => $safeMsg,
                    'status' => $status,
                    'response' => json_encode($serviceResponse['response'] ?? []),
                ]);
            }, 3);

            return [
                'status' => 200,
                'body' => [
                    'success' => $serviceResponse['success'],
                    'message' => ucfirst($type) . ' queued successfully.',
                    'data' => $serviceResponse['response'] ?? [],
                ],
            ];
        } catch (\Exception) {
            try {
                DB::transaction(function () use ($organization, $cost, $type, $phone, $message, $reservationRef, $user) {
                    $maskedPhone = $this->maskPhone($phone);
                    $safeMsg = $this->safeMessageForLog($message);
                    $trx = WalletTransaction::query()
                        ->where('organization_id', $organization->id)
                        ->where('reference_id', $reservationRef)
                        ->where('type', 'debit')
                        ->where('status', 'pending')
                        ->lockForUpdate()
                        ->first();
                    if ($trx) {
                        $organization->increment('wallet_balance', $cost);
                        $trx->update([
                            'status' => 'failed',
                            'description' => strtoupper($type) . ' error — reservation refunded for ' . $maskedPhone,
                        ]);
                    }

                    ApiUsageLog::create([
                        'user_id' => $user->id,
                        'organization_id' => $organization->id,
                        'type' => $type,
                        'phone' => $maskedPhone,
                        'message' => $safeMsg,
                        'status' => 'failed',
                        'response' => json_encode(['exception' => 'external_service_error']),
                    ]);
                }, 3);
            } catch (\Throwable) {
                // swallow
            }

            return ['status' => 500, 'body' => ['error' => 'Something went wrong. Please try again later.']];
        }
    }

    private function maskPhone(string $phone): string
    {
        $digits = preg_replace('/\D+/', '', $phone) ?? '';
        if ($digits === '') {
            return '';
        }
        if (strlen($digits) <= 5) {
            return str_repeat('*', strlen($digits));
        }

        return substr($digits, 0, 5) . str_repeat('*', max(0, strlen($digits) - 5));
    }

    private function safeMessageForLog(string $message): string
    {
        $message = trim($message);
        if ($message === '') {
            return '';
        }

        return mb_substr($message, 0, 120);
    }
}
