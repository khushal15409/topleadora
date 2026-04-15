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

        // Check api access restriction
        if (!$organization->api_access_enabled) {
            return response()->json([
                'error' => 'API Access is disabled for this organization.'
            ], 403);
        }

        $cost = $type === 'otp' ? self::OTP_COST : self::WA_COST;

        // Ensure wallet balance
        if ($organization->wallet_balance < $cost) {
            return response()->json([
                'error' => 'Insufficient wallet balance.'
            ], 402); // 402 Payment Required
        }

        // Process request
        try {
            $serviceResponse = $type === 'otp'
                ? $this->otpService->send($validated['phone'], $validated['message'])
                : $this->whatsappService->send($validated['phone'], $validated['message']);

            $status = $serviceResponse['success'] ? 'success' : 'failed';

            DB::transaction(function () use ($user, $organization, $type, $validated, $cost, $status, $serviceResponse) {
                // Deduct Balance
                if ($status === 'success') {
                    $organization->decrement('wallet_balance', $cost);

                    WalletTransaction::create([
                        'organization_id' => $organization->id,
                        'amount' => $cost,
                        'type' => 'debit',
                        'description' => strtoupper($type) . ' usage deduction for ' . $validated['phone']
                    ]);
                }

                // Log Usage
                ApiUsageLog::create([
                    'user_id' => $user->id,
                    'organization_id' => $organization->id,
                    'type' => $type,
                    'phone' => $validated['phone'],
                    'message' => $validated['message'],
                    'status' => $status,
                    'response' => json_encode($serviceResponse['response'] ?? []),
                ]);
            });

            return response()->json([
                'success' => $serviceResponse['success'],
                'message' => ucfirst($type) . ' queued successfully.',
                'data' => $serviceResponse['response'] ?? []
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred while processing the request.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }
}
