<?php

namespace App\Http\Controllers\Webhooks;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\Payment;
use App\Models\Plan;
use App\Services\RazorpayService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

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
                $reason = (string) ($paymentEntity['error_description'] ?? $paymentEntity['error_reason'] ?? 'Payment failed');
                $payment->forceFill([
                    'razorpay_payment_id' => $paymentId !== '' ? $paymentId : $payment->razorpay_payment_id,
                    'status' => Payment::STATUS_FAILED,
                    'failure_reason' => $reason !== '' ? mb_substr($reason, 0, 500) : 'Payment failed',
                    'meta' => array_merge((array) ($payment->meta ?? []), [
                        'webhook' => [
                            'event' => $type,
                            'payment' => $paymentEntity,
                        ],
                    ]),
                ])->save();
            }

            return response('OK', 200);
        }

        if ($type === 'payment.captured') {
            try {
                DB::transaction(function () use ($payment, $paymentId, $paymentEntity, $razorpay, $orderId, $type) {
                    /** @var Payment $p */
                    $p = Payment::query()->whereKey($payment->id)->lockForUpdate()->firstOrFail();

                    if ($p->status === Payment::STATUS_SUCCESS) {
                        return;
                    }

                    // Validate amount/currency from webhook vs expected payment row.
                    $expectedPaise = (int) round(((float) $p->amount) * 100);
                    $rzpAmount = (int) ($paymentEntity['amount'] ?? 0);
                    $rzpCurrency = (string) ($paymentEntity['currency'] ?? 'INR');
                    $rzpStatus = (string) ($paymentEntity['status'] ?? '');

                    // Extra validation: fetch order if possible (best-effort).
                    $order = [];
                    try {
                        $order = $razorpay->fetchOrder($orderId);
                    } catch (\Throwable) {
                        $order = [];
                    }
                    $orderAmount = (int) ($order['amount'] ?? $rzpAmount);
                    $orderCurrency = (string) ($order['currency'] ?? $rzpCurrency);

                    if ($rzpStatus !== 'captured' || $rzpAmount !== $expectedPaise || $orderAmount !== $expectedPaise || strtoupper($rzpCurrency) !== strtoupper((string) $p->currency) || strtoupper($orderCurrency) !== strtoupper((string) $p->currency)) {
                        $p->forceFill([
                            'razorpay_payment_id' => $paymentId !== '' ? $paymentId : $p->razorpay_payment_id,
                            'status' => Payment::STATUS_FAILED,
                            'failure_reason' => 'Webhook validation failed (amount/currency/status mismatch)',
                            'meta' => array_merge((array) ($p->meta ?? []), [
                                'webhook' => [
                                    'event' => $type,
                                    'payment' => $paymentEntity,
                                    'order' => $razorpay->orderMeta($order),
                                ],
                            ]),
                        ])->save();

                        return;
                    }

                    $p->forceFill([
                        'razorpay_payment_id' => $paymentId !== '' ? $paymentId : $p->razorpay_payment_id,
                        'status' => Payment::STATUS_SUCCESS,
                        'failure_reason' => null,
                        'paid_at' => $p->paid_at ?? now(),
                        'meta' => array_merge((array) ($p->meta ?? []), [
                            'webhook' => [
                                'event' => $type,
                                'payment' => $paymentEntity,
                                'order' => $razorpay->orderMeta($order),
                            ],
                        ]),
                    ])->save();

                    if ($p->subscription_id === null) {
                        $plan = Plan::query()->whereKey($p->plan_id)->first();
                        $org = Organization::query()->whereKey($p->organization_id)->first();
                        if ($plan && $plan->is_active && $org) {
                            $org->activateSubscriptionFromPayment($plan, $p);
                        }
                    }
                }, 3);
            } catch (\Throwable) {
                // Webhook should be idempotent; return OK to avoid repeated gateway retries storming logs.
            }

            return response('OK', 200);
        }

        return response('OK', 200);
    }
}
