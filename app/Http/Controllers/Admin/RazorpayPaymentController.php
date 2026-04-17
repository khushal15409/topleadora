<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Plan;
use App\Services\RazorpayService;
use App\Support\Roles;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class RazorpayPaymentController extends Controller
{
    public function createOrder(Request $request, Plan $plan, RazorpayService $razorpay): JsonResponse
    {
        abort_if(!paymentEnabled(), 404);

        $user = $request->user();
        abort_unless($user && ($user->hasRole(Roles::ORGANIZATION) || isSuperAdmin()), 403);
        abort_unless($plan->is_active, 404);
        abort_unless($razorpay->isConfigured(), 422);

        $user->loadMissing('organization');
        $organization = $user->organization;
        abort_if($organization === null, 403);

        // Global rule: backend storage/calculation currency is INR.
        // Razorpay is used only in INR for this SaaS.
        $currency = 'INR';
        $amount = (float) $plan->price_monthly;
        $amountPaise = (int) round($amount * 100);
        abort_if($amountPaise <= 0, 422);

        $receipt = 'sub_' . $organization->id . '_plan_' . $plan->id . '_' . Str::random(10);

        try {
            $orderPayload = $razorpay->createOrder($amountPaise, $currency, $receipt, [
                'organization_id' => (string) $organization->id,
                'plan_id' => (string) $plan->id,
                'user_id' => (string) $user->id,
            ]);

            Log::info('[Subscription] Razorpay Order Created', [
                'order_id' => $orderPayload['id'] ?? 'N/A',
                'amount' => $orderPayload['amount'] ?? 0,
                'plan_id' => $plan->id
            ]);

        } catch (\Throwable $e) {
            report($e);
            return response()->json(['message' => 'Unable to create payment order right now. Please try again later.'], 500);
        }

        $payment = Payment::query()->create([
            'user_id' => $user->id,
            'organization_id' => $organization->id,
            'plan_id' => $plan->id,
            'subscription_id' => null,
            'amount' => $amount,
            'currency' => $currency,
            'gateway' => 'razorpay',
            'razorpay_order_id' => (string) ($orderPayload['id'] ?? ''),
            'status' => Payment::STATUS_PENDING,
            'meta' => $razorpay->orderMeta($orderPayload),
            'paid_at' => null,
        ]);

        return response()->json([
            'key' => $razorpay->key(),
            'amount' => $orderPayload['amount'],
            'currency' => $orderPayload['currency'],
            'order_id' => $payment->razorpay_order_id,
            'name' => config('app.name', 'CRM'),
            'description' => 'Subscription: ' . $plan->name,
            'prefill' => [
                'name' => (string) ($user->name ?? ''),
                'email' => (string) ($user->email ?? ''),
                'contact' => (string) ($user->phone ?? ''),
            ],
            'notes' => [
                'organization_id' => (string) $organization->id,
                'plan_id' => (string) $plan->id,
                'payment_row_id' => (string) $payment->id,
            ],
        ]);
    }

    public function verify(Request $request, RazorpayService $razorpay): RedirectResponse|JsonResponse
    {
        abort_if(!paymentEnabled(), 404);

        $user = $request->user();
        abort_unless($user && $user->hasRole(Roles::ORGANIZATION), 403);
        abort_unless($razorpay->isConfigured(), 422);

        $data = $request->validate([
            'razorpay_order_id' => ['required', 'string', 'max:100'],
            'razorpay_payment_id' => ['required', 'string', 'max:100'],
            'razorpay_signature' => ['required', 'string', 'max:255'],
        ]);

        $user->loadMissing('organization');
        $organization = $user->organization;
        abort_if($organization === null, 403);

        $result = null;
        try {
            $result = DB::transaction(function () use ($data, $organization, $user, $razorpay) {
                /** @var Payment|null $payment */
                $payment = Payment::query()
                    ->where('gateway', 'razorpay')
                    ->where('razorpay_order_id', $data['razorpay_order_id'])
                    ->lockForUpdate()
                    ->first();

                if ($payment === null || (int) $payment->organization_id !== (int) $organization->id) {
                    return ['ok' => false, 'status' => 404, 'message' => 'Payment not found.'];
                }

                if ($payment->status === Payment::STATUS_SUCCESS) {
                    return ['ok' => true, 'already' => true, 'payment' => $payment];
                }

                $isValid = $razorpay->verifyPaymentSignature([
                    'razorpay_order_id' => $data['razorpay_order_id'],
                    'razorpay_payment_id' => $data['razorpay_payment_id'],
                    'razorpay_signature' => $data['razorpay_signature'],
                ]);

                if (!$isValid) {
                    $payment->forceFill([
                        'razorpay_payment_id' => $data['razorpay_payment_id'],
                        'razorpay_signature' => $data['razorpay_signature'],
                        'status' => Payment::STATUS_FAILED,
                        'failure_reason' => 'Signature verification failed',
                    ])->save();

                    return ['ok' => false, 'status' => 422, 'message' => 'Signature verification failed.'];
                }

                // Gateway-side validation: ensure order & payment match expected amount/currency and are captured.
                $order = $razorpay->fetchOrder($data['razorpay_order_id']);
                $rzpAmount = (int) ($order['amount'] ?? 0); // paise
                $rzpCurrency = (string) ($order['currency'] ?? 'INR');
                $expectedPaise = (int) round(((float) $payment->amount) * 100);

                $pay = $razorpay->fetchPayment($data['razorpay_payment_id']);
                $payStatus = (string) ($pay['status'] ?? '');
                $payOrderId = (string) ($pay['order_id'] ?? '');

                if ($payOrderId !== $data['razorpay_order_id'] || $payStatus !== 'captured' || $rzpAmount !== $expectedPaise || strtoupper($rzpCurrency) !== strtoupper((string) $payment->currency)) {
                    $payment->forceFill([
                        'razorpay_payment_id' => $data['razorpay_payment_id'],
                        'razorpay_signature' => $data['razorpay_signature'],
                        'status' => Payment::STATUS_FAILED,
                        'failure_reason' => 'Gateway validation failed (amount/currency/status mismatch)',
                        'meta' => array_merge((array) ($payment->meta ?? []), [
                            'order' => $razorpay->orderMeta($order),
                            'payment' => $pay,
                        ]),
                    ])->save();

                    return ['ok' => false, 'status' => 422, 'message' => 'Payment validation failed.'];
                }

                $payment->forceFill([
                    'user_id' => $user->id,
                    'razorpay_payment_id' => $data['razorpay_payment_id'],
                    'razorpay_signature' => $data['razorpay_signature'],
                    'status' => Payment::STATUS_SUCCESS,
                    'failure_reason' => null,
                    'paid_at' => now(),
                    'meta' => array_merge((array) ($payment->meta ?? []), [
                        'order' => $razorpay->orderMeta($order),
                        'payment' => $pay,
                    ]),
                ])->save();

                $plan = Plan::query()->whereKey($payment->plan_id)->first();
                if ($plan === null || !$plan->is_active) {
                    return ['ok' => false, 'status' => 404, 'message' => 'Plan not found.'];
                }

                if ($payment->subscription_id === null) {
                    $organization->activateSubscriptionFromPayment($plan, $payment);
                }

                return ['ok' => true, 'payment' => $payment];
            }, 3);
        } catch (\Throwable $e) {
            report($e);
            $result = ['ok' => false, 'status' => 500, 'message' => 'Verification failed. Please try again.'];
        }

        if (($result['ok'] ?? false) !== true) {
            $status = (int) ($result['status'] ?? 422);
            $message = (string) ($result['message'] ?? 'Verification failed.');

            $planId = null;
            if (isset($result['payment']) && $result['payment'] instanceof Payment) {
                $planId = $result['payment']->plan_id;
            } else {
                $planId = Payment::query()
                    ->where('gateway', 'razorpay')
                    ->where('razorpay_order_id', $data['razorpay_order_id'])
                    ->value('plan_id');
            }

            return $request->expectsJson()
                ? response()->json(['message' => $message], $status)
                : ($planId
                    ? redirect()->route('admin.subscription.checkout', $planId)->with('error', __($message))
                    : redirect()->route('admin.subscription.pricing')->with('error', __($message)));
        }

        if ($request->expectsJson()) {
            return response()->json(['ok' => true]);
        }

        return redirect()
            ->route('admin.dashboard')
            ->with('success', __('Payment successful. Your subscription is active.'))
            ->with('track_google_conversion', true);
    }
}
