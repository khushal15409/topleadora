<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ApiUsageLog;
use App\Models\WalletTransaction;
use App\Services\OtpService;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GatewayController extends Controller
{
    protected OtpService $otpService;
    protected WhatsAppService $whatsappService;

    // A constant deduction rate for simulation purposes, real SaaS could have dynamic models
    protected const OTP_COST = 0.50; // $0.50 per OTP
    protected const WA_COST = 1.00; // $1.00 per standard WA API call

    public function __construct(OtpService $otpService, WhatsAppService $whatsappService)
    {
        $this->otpService = $otpService;
        $this->whatsappService = $whatsappService;
    }

    public function sendOtp(Request $request): JsonResponse
    {
        return $this->process('otp', $request, [
            'phone' => 'required|string',
            'message' => 'required|string',
        ]);
    }

    public function sendWhatsapp(Request $request): JsonResponse
    {
        return $this->process('whatsapp', $request, [
            'phone' => 'required|string',
            'message' => 'required|string',
        ]);
    }

    protected function process(string $type, Request $request, array $rules): JsonResponse
    {
        $validated = $request->validate($rules);

        $user = $request->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        $organization = $user->organization;
        if (!$organization) {
            return response()->json(['error' => 'No organization found.'], 403);
        }

        $cost = $type === 'otp' ? self::OTP_COST : self::WA_COST;
        $reservationRef = (string) Str::uuid();

        // Reserve funds BEFORE calling external services to avoid negative balances under concurrency.
        try {
            $reserved = DB::transaction(function () use ($organization, $cost, $type, $validated, $reservationRef) {
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

                WalletTransaction::create([
                    'organization_id' => $org->id,
                    'amount' => $cost,
                    'type' => 'debit',
                    'source' => 'api_usage',
                    'reference_id' => $reservationRef,
                    'description' => strtoupper($type) . ' usage (reserved) for ' . $validated['phone'],
                    'status' => 'pending',
                ]);

                return ['ok' => true];
            }, 3);

            if (($reserved['ok'] ?? false) !== true) {
                return response()->json(['error' => (string) ($reserved['message'] ?? 'Unable to reserve balance.')], (int) ($reserved['status'] ?? 402));
            }
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Unable to reserve wallet balance. Please retry.'], 500);
        }

        // Process request (external call happens after balance is safely reserved).
        try {
            $serviceResponse = $type === 'otp'
                ? $this->otpService->send($validated['phone'], $validated['message'])
                : $this->whatsappService->send($validated['phone'], $validated['message']);

            $status = $serviceResponse['success'] ? 'success' : 'failed';

            DB::transaction(function () use ($user, $organization, $type, $validated, $cost, $status, $serviceResponse, $reservationRef) {
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
                            'description' => strtoupper($type) . ' usage deduction for ' . $validated['phone'],
                        ]);
                    } else {
                        // Refund reservation on failure.
                        $organization->increment('wallet_balance', $cost);
                        $trx->update([
                            'status' => 'failed',
                            'description' => strtoupper($type) . ' failed — reservation refunded for ' . $validated['phone'],
                        ]);
                    }
                } else {
                    // Safety fallback: if reservation row is missing, do not attempt another deduction.
                    if ($status !== 'success') {
                        $organization->increment('wallet_balance', $cost);
                    }
                }

                ApiUsageLog::create([
                    'user_id' => $user->id,
                    'organization_id' => $organization->id,
                    'type' => $type,
                    'phone' => $validated['phone'],
                    'message' => $validated['message'],
                    'status' => $status,
                    'response' => json_encode($serviceResponse['response'] ?? []),
                ]);
            }, 3);

            return response()->json([
                'success' => $serviceResponse['success'],
                'message' => ucfirst($type) . ' queued successfully.',
                'data' => $serviceResponse['response'] ?? []
            ]);

        } catch (\Exception $e) {
            // If the external call threw, refund the reservation.
            try {
                DB::transaction(function () use ($organization, $cost, $type, $validated, $reservationRef, $e) {
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
                            'description' => strtoupper($type) . ' error — reservation refunded for ' . ($validated['phone'] ?? ''),
                        ]);
                    }

                    ApiUsageLog::create([
                        'user_id' => $request->user()?->id,
                        'organization_id' => $organization->id,
                        'type' => $type,
                        'phone' => (string) ($validated['phone'] ?? ''),
                        'message' => (string) ($validated['message'] ?? ''),
                        'status' => 'failed',
                        'response' => json_encode(['exception' => $e->getMessage()]),
                    ]);
                }, 3);
            } catch (\Throwable) {
                // swallow
            }

            return response()->json([
                'error' => 'An error occurred while processing the request.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }
}
