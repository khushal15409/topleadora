<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\ApiGatewaySendService;
use App\Services\WhatsAppCloudApiService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ApiTestSendController extends Controller
{
    private const COMBINED_TEST_MIN_INR = 1.50;

    public function index(Request $request, WhatsAppCloudApiService $wa): View
    {
        $organization = $request->user()->organization;
        $whatsappConfigured = $wa->isConfigured();

        return view('admin.api.test-send', [
            'organization' => $organization,
            'whatsappConfigured' => $whatsappConfigured,
        ]);
    }

    public function store(Request $request, ApiGatewaySendService $gatewaySendService): RedirectResponse
    {
        $validated = $request->validate([
            'phone' => ['required', 'string', 'max:32'],
            'otp' => ['required', 'digits:4'],
        ]);

        $user = $request->user();
        $organization = $user->organization;
        if (!$organization) {
            return back()->withErrors(['phone' => __('No organization is linked to your account.')]);
        }

        if (!$organization->api_access_enabled) {
            return back()->withErrors(['phone' => __('API access is disabled for your organization.')]);
        }

        if ((float) $organization->wallet_balance < self::COMBINED_TEST_MIN_INR) {
            return back()->withErrors([
                'phone' => __('Your wallet must be at least :amount INR to run this test (OTP channel + WhatsApp).', [
                    'amount' => number_format(self::COMBINED_TEST_MIN_INR, 2),
                ]),
            ]);
        }

        $message = __('Your verification OTP is :otp. Do not share it with anyone.', [
            'otp' => $validated['otp'],
        ]);

        $otpResult = $gatewaySendService->execute('otp', $user, $validated['phone'], $message);
        $waResult = $gatewaySendService->execute('whatsapp', $user, $validated['phone'], $message);

        return back()
            ->withInput($request->only('phone'))
            ->with('test_send_otp', $otpResult)
            ->with('test_send_whatsapp', $waResult);
    }
}
