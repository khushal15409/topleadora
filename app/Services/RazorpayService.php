<?php

namespace App\Services;

use Illuminate\Support\Arr;
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

class RazorpayService
{
    public function isConfigured(): bool
    {
        return (string) setting('razorpay_key', '') !== '' && (string) setting('razorpay_secret', '') !== '';
    }

    public function key(): string
    {
        return (string) setting('razorpay_key', '');
    }

    private function secret(): string
    {
        return (string) setting('razorpay_secret', '');
    }

    private function api(): Api
    {
        $key = $this->key();
        $secret = $this->secret();

        if ($key === '' || $secret === '') {
            throw new \RuntimeException('Razorpay is not configured.');
        }

        return new Api($key, $secret);
    }

    /**
     * @return array<string, mixed>
     */
    public function fetchOrder(string $orderId): array
    {
        $order = $this->api()->order->fetch($orderId);

        /** @var array<string, mixed> $payload */
        $payload = $order->toArray();

        return $payload;
    }

    /**
     * @return array<string, mixed>
     */
    public function fetchPayment(string $paymentId): array
    {
        $payment = $this->api()->payment->fetch($paymentId);

        /** @var array<string, mixed> $payload */
        $payload = $payment->toArray();

        return $payload;
    }

    /**
     * @param  array<string, mixed>  $notes
     * @return array<string, mixed>
     */
    public function createOrder(int $amountPaise, string $currency, string $receipt, array $notes = []): array
    {
        $order = $this->api()->order->create([
            'amount' => $amountPaise,
            'currency' => $currency,
            'receipt' => $receipt,
            'notes' => $notes,
        ]);

        /** @var array<string, mixed> $payload */
        $payload = $order->toArray();

        return $payload;
    }

    /**
     * @param  array<string, string>  $attributes
     */
    public function verifyPaymentSignature(array $attributes): bool
    {
        try {
            $this->api()->utility->verifyPaymentSignature($attributes);

            return true;
        } catch (SignatureVerificationError) {
            return false;
        }
    }

    public function verifyWebhookSignature(string $payload, string $signatureHeader): bool
    {
        try {
            $this->api()->utility->verifyWebhookSignature($payload, $signatureHeader, $this->secret());

            return true;
        } catch (SignatureVerificationError) {
            return false;
        }
    }

    /**
     * Extract safe fields for storage/debugging.
     *
     * @param  array<string, mixed>  $orderPayload
     * @return array<string, mixed>
     */
    public function orderMeta(array $orderPayload): array
    {
        return Arr::only($orderPayload, ['id', 'amount', 'currency', 'receipt', 'status', 'created_at', 'notes']);
    }
}
