<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ApiGatewaySendService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class GatewayController extends Controller
{
    public function __construct(protected ApiGatewaySendService $gatewaySendService)
    {
    }

    public function sendOtp(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'phone' => 'required|string',
            'message' => 'required|string',
        ]);

        $user = $request->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        $out = $this->gatewaySendService->execute('otp', $user, $validated['phone'], $validated['message']);

        return response()->json($out['body'], $out['status']);
    }

    public function sendWhatsapp(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'phone' => 'required|string',
            'message' => 'required|string',
        ]);

        $user = $request->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        $out = $this->gatewaySendService->execute('whatsapp', $user, $validated['phone'], $validated['message']);

        return response()->json($out['body'], $out['status']);
    }
}
