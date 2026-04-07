<?php

namespace App\Http\Controllers\Webhooks;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\Payment;
use App\Models\Plan;
use App\Services\RazorpayService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RazorpayWebhookController extends Controller
{
    public function __invoke(Request $request, RazorpayService $razorpay): Response
    {
        if (! $razorpay->isConfigured()) {
            return response('Not configured', 422);
        }

        $signature = (string) $request->header('X-Razorpay-Signature', '');
        $payload = (string) $request->getContent();

        if ($signature === '' || ! $razorpay->verifyWebhookSignature($payload, $signature)) {
            return response('Invalid signature', 401);
        }

        /** @var array<string, mixed> $event */
        $event = (array) $request->all();
        $type = (string) ($event['event'] ?? '');

        $paymentEntity = $event['payload']['payment']['entity'] ?? null;
        if (! is_array($paymentEntity)) {
            return response('OK', 200);
        }

        $orderId = (string) ($paymentEntity['order_id'] ?? '');
        $paymentId = (string) ($paymentEntity['id'] ?? '');

        if ($orderId === '') {
            return response('OK', 200);
        }

        /** @var Payment|null $payment */
        $payment = Payment::query()
            ->where('gateway', 'razorpay')
            ->where('razorpay_order_id', $orderId)
            ->first();

        if ($payment === null) {
            return response('OK', 200);
        }

        if ($type === 'payment.failed') {
            if ($payment->status !== Payment::STATUS_SUCCESS) {
                $payment->forceFill([
                    'razorpay_payment_id' => $paymentId !== '' ? $paymentId : $payment->razorpay_payment_id,
                    'status' => Payment::STATUS_FAILED,
                ])->save();
            }

            return response('OK', 200);
        }

        if ($type === 'payment.captured') {
            if ($payment->status !== Payment::STATUS_SUCCESS) {
                $payment->forceFill([
                    'razorpay_payment_id' => $paymentId !== '' ? $paymentId : $payment->razorpay_payment_id,
                    'status' => Payment::STATUS_SUCCESS,
                    'paid_at' => $payment->paid_at ?? now(),
                ])->save();
            }

            if ($payment->subscription_id === null) {
                $plan = Plan::query()->whereKey($payment->plan_id)->first();
                $org = Organization::query()->whereKey($payment->organization_id)->first();
                if ($plan && $plan->is_active && $org) {
                    $org->activateSubscriptionFromPayment($plan, $payment);
                }
            }

            return response('OK', 200);
        }

        return response('OK', 200);
    }
}
