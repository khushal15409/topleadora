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
use Illuminate\Support\Str;

class RazorpayPaymentController extends Controller
{
    public function createOrder(Request $request, Plan $plan, RazorpayService $razorpay): JsonResponse
    {
        abort_if(! paymentEnabled(), 404);

        $user = $request->user();
        abort_unless($user && $user->hasRole(Roles::ORGANIZATION), 403);
        abort_unless($plan->is_active, 404);
        abort_unless($razorpay->isConfigured(), 422);

        $user->loadMissing('organization');
        $organization = $user->organization;
        abort_if($organization === null, 403);

        $currency = (string) ($plan->currency ?: 'INR');
        $amount = (float) $plan->price_monthly;
        $amountPaise = (int) round($amount * 100);
        abort_if($amountPaise <= 0, 422);

        $receipt = 'sub_'.$organization->id.'_plan_'.$plan->id.'_'.Str::random(10);

        try {
            $orderPayload = $razorpay->createOrder($amountPaise, $currency, $receipt, [
                'organization_id' => (string) $organization->id,
                'plan_id' => (string) $plan->id,
                'user_id' => (string) $user->id,
            ]);
        } catch (\Throwable $e) {
            report($e);
            return response()->json(['message' => 'Unable to create payment order.'], 500);
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
            'amount' => $amountPaise,
            'currency' => $currency,
            'order_id' => $payment->razorpay_order_id,
            'name' => config('app.name', 'CRM'),
            'description' => 'Subscription: '.$plan->name,
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
        abort_if(! paymentEnabled(), 404);

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

        /** @var Payment|null $payment */
        $payment = Payment::query()
            ->where('gateway', 'razorpay')
            ->where('razorpay_order_id', $data['razorpay_order_id'])
            ->first();

        if ($payment === null || (int) $payment->organization_id !== (int) $organization->id) {
            abort(404);
        }

        if ($payment->status === Payment::STATUS_SUCCESS) {
            return $request->expectsJson()
                ? response()->json(['ok' => true])
                : redirect()->route('admin.dashboard')->with('success', __('Payment already verified.'));
        }

        $isValid = $razorpay->verifyPaymentSignature([
            'razorpay_order_id' => $data['razorpay_order_id'],
            'razorpay_payment_id' => $data['razorpay_payment_id'],
            'razorpay_signature' => $data['razorpay_signature'],
        ]);

        if (! $isValid) {
            $payment->forceFill([
                'razorpay_payment_id' => $data['razorpay_payment_id'],
                'razorpay_signature' => $data['razorpay_signature'],
                'status' => Payment::STATUS_FAILED,
            ])->save();

            return $request->expectsJson()
                ? response()->json(['message' => 'Signature verification failed.'], 422)
                : redirect()->route('admin.subscription.checkout', $payment->plan_id)->with('error', __('Payment verification failed. Please try again.'));
        }

        // Prevent duplicate payment IDs.
        $dupe = Payment::query()
            ->where('gateway', 'razorpay')
            ->where('razorpay_payment_id', $data['razorpay_payment_id'])
            ->whereKeyNot($payment->id)
            ->exists();
        abort_if($dupe, 409);

        $payment->forceFill([
            'user_id' => $user->id,
            'razorpay_payment_id' => $data['razorpay_payment_id'],
            'razorpay_signature' => $data['razorpay_signature'],
            'status' => Payment::STATUS_SUCCESS,
            'paid_at' => now(),
        ])->save();

        $plan = Plan::query()->whereKey($payment->plan_id)->first();
        abort_if($plan === null || ! $plan->is_active, 404);

        if ($payment->subscription_id === null) {
            $organization->activateSubscriptionFromPayment($plan, $payment);
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

