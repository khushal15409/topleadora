<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\WalletTransaction;
use App\Services\RazorpayService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ApiWalletController extends Controller
{
    public function index(Request $request)
    {
        $organization = $request->user()->organization;

        if ($organization === null) {
            abort(403, 'You must be associated with an organization to access the wallet.');
        }

        $transactions = WalletTransaction::where('organization_id', $organization->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $razorpayConfigured = app(RazorpayService::class)->isConfigured();

        return view('admin.api.wallet', compact('organization', 'transactions', 'razorpayConfigured'));
    }

    /**
     * Create a Razorpay order for wallet top-up.
     */
    public function createOrder(Request $request, RazorpayService $razorpay): JsonResponse
    {
        abort_unless(isApiPaymentEnabled(), 403, 'API payments are not available.');
        abort_unless($razorpay->isConfigured(), 422, 'Payment gateway is not configured.');

        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:100', 'max:100000'],
        ]);

        $amount = (float) $validated['amount'];
        $amountPaise = (int) round($amount * 100);

        $user = $request->user();
        $organization = $user->organization;
        abort_if($organization === null, 403, 'No organization found.');

        $receipt = 'wallet_' . $organization->id . '_' . Str::random(8);

        try {
            $orderPayload = $razorpay->createOrder($amountPaise, 'INR', $receipt, [
                'organization_id' => (string) $organization->id,
                'purpose' => 'wallet_topup',
                'user_id' => (string) $user->id,
            ]);

            // Debug log to catch 400 Bad Request sources
            Log::info('[WalletTopUp] Razorpay Order Created', [
                'order_id' => $orderPayload['id'] ?? 'N/A',
                'amount' => $orderPayload['amount'] ?? 0,
                'org_id' => $organization->id
            ]);

        } catch (\Throwable $e) {
            Log::error('[WalletTopUp] Razorpay order creation failed: ' . $e->getMessage());
            return response()->json(['message' => 'Unable to initiate payment: ' . $e->getMessage()], 500);
        }

        // Create a pending transaction record
        WalletTransaction::create([
            'organization_id' => $organization->id,
            'amount' => $amount,
            'type' => 'credit',
            'source' => 'recharge',
            // Keep legacy reference_id for display, but store structured Razorpay IDs separately.
            'reference_id' => $orderPayload['id'] ?? null,
            'razorpay_order_id' => $orderPayload['id'] ?? null,
            'description' => 'Wallet top-up (pending)',
            'status' => 'pending',
            'meta' => $razorpay->orderMeta($orderPayload),
        ]);

        $phone = preg_replace('/[^0-9]/', '', (string) ($user->phone ?? ''));
        if (strlen($phone) > 10) {
            $phone = substr($phone, -10);
        }

        return response()->json([
            'key' => (string) $razorpay->key(),
            'order_id' => (string) $orderPayload['id'],
            'amount' => (int) $orderPayload['amount'], // paise returned from RZP
            'currency' => 'INR',
            'name' => (string) config('app.name', 'WP-CRM'),
            'description' => 'Wallet top-up credits',
            'prefill' => [
                'name' => (string) ($user->name ?? 'User'),
                'email' => (string) ($user->email ?? ''),
                'contact' => $phone,
            ],
            'notes' => [
                'organization_id' => (string) $organization->id,
                'user_id' => (string) $user->id,
                'purpose' => 'recharge',
            ],
        ]);
    }

    /**
     * Verify the Razorpay payment and credit the wallet.
     */
    public function verifyPayment(Request $request, RazorpayService $razorpay): JsonResponse
    {
        abort_unless(isApiPaymentEnabled(), 403, 'API payments are not available.');
        abort_unless($razorpay->isConfigured(), 422, 'Payment gateway is not configured.');

        $data = $request->validate([
            'razorpay_order_id' => ['required', 'string', 'max:100'],
            'razorpay_payment_id' => ['required', 'string', 'max:100'],
            'razorpay_signature' => ['required', 'string', 'max:255'],
        ]);

        $user = $request->user();
        $organization = $user->organization;
        abort_if($organization === null, 403);

        // Idempotency: if we already processed this Razorpay payment id for this org, return success.
        $already = WalletTransaction::query()
            ->where('organization_id', $organization->id)
            ->where('razorpay_payment_id', $data['razorpay_payment_id'])
            ->where('status', 'success')
            ->first();
        if ($already !== null) {
            return response()->json([
                'ok' => true,
                'message' => 'Wallet already credited.',
                'new_balance' => number_format($organization->fresh()->wallet_balance, 2),
            ]);
        }

        try {
            $result = DB::transaction(function () use ($organization, $data, $razorpay) {
                /** @var WalletTransaction|null $transaction */
                $transaction = WalletTransaction::query()
                    ->where('organization_id', $organization->id)
                    ->where(function ($q) use ($data) {
                        $q->where('razorpay_order_id', $data['razorpay_order_id'])
                          ->orWhere('reference_id', $data['razorpay_order_id']); // legacy rows
                    })
                    ->where('status', 'pending')
                    ->lockForUpdate()
                    ->first();

                if ($transaction === null) {
                    return ['ok' => false, 'status' => 404, 'message' => 'Transaction not found or already processed.'];
                }

                // Prevent replay attacks: verify signature server-side.
                $isValid = $razorpay->verifyPaymentSignature([
                    'razorpay_order_id' => $data['razorpay_order_id'],
                    'razorpay_payment_id' => $data['razorpay_payment_id'],
                    'razorpay_signature' => $data['razorpay_signature'],
                ]);
                if (!$isValid) {
                    $transaction->update([
                        'status' => 'failed',
                        'description' => 'Wallet top-up — signature mismatch',
                        'razorpay_payment_id' => $data['razorpay_payment_id'],
                        'razorpay_signature' => $data['razorpay_signature'],
                    ]);

                    return ['ok' => false, 'status' => 422, 'message' => 'Payment signature verification failed.'];
                }

                // Gateway-side verification: ensure payment is captured and amount matches the pending transaction.
                $payment = $razorpay->fetchPayment($data['razorpay_payment_id']);
                $orderId = (string) ($payment['order_id'] ?? '');
                $status = (string) ($payment['status'] ?? '');
                $currency = (string) ($payment['currency'] ?? 'INR');
                $amountPaid = (int) ($payment['amount'] ?? 0); // paise
                $expected = (int) round(((float) $transaction->amount) * 100);

                if ($orderId !== $data['razorpay_order_id'] || $status !== 'captured' || strtoupper($currency) !== 'INR' || $amountPaid !== $expected) {
                    $transaction->update([
                        'status' => 'failed',
                        'description' => 'Wallet top-up — gateway validation failed',
                        'razorpay_order_id' => $data['razorpay_order_id'],
                        'razorpay_payment_id' => $data['razorpay_payment_id'],
                        'razorpay_signature' => $data['razorpay_signature'],
                        'meta' => $payment,
                    ]);

                    return ['ok' => false, 'status' => 422, 'message' => 'Payment validation failed. If money was deducted, contact support.'];
                }

                $transaction->update([
                    'status' => 'success',
                    'description' => 'Wallet top-up via Razorpay',
                    'razorpay_order_id' => $data['razorpay_order_id'],
                    'razorpay_payment_id' => $data['razorpay_payment_id'],
                    'razorpay_signature' => $data['razorpay_signature'],
                    'meta' => $payment,
                ]);

                $organization->increment('wallet_balance', (float) $transaction->amount);

                return ['ok' => true];
            }, 3);

            if (($result['ok'] ?? false) !== true) {
                return response()->json(['message' => (string) ($result['message'] ?? 'Payment verification failed.')], (int) ($result['status'] ?? 422));
            }
        } catch (\Throwable $e) {
            Log::error('[WalletTopUp] Verify/Credit failed: ' . $e->getMessage());
            return response()->json(['message' => 'Payment captured but wallet update failed. Contact support immediately.'], 500);
        }

        return response()->json([
            'ok' => true,
            'message' => 'Wallet credited successfully!',
            'new_balance' => number_format($organization->fresh()->wallet_balance, 2),
        ]);
    }

    /**
     * Log payment failure from frontend.
     */
    public function logError(Request $request): JsonResponse
    {
        $data = $request->validate([
            'order_id' => ['nullable', 'string'],
            'payment_id' => ['nullable', 'string'],
            'error_code' => ['nullable', 'string'],
            'error_description' => ['nullable', 'string'],
            'error_source' => ['nullable', 'string'],
            'error_step' => ['nullable', 'string'],
            'error_reason' => ['nullable', 'string'],
            'metadata' => ['nullable', 'array'],
        ]);

        $user = $request->user();

        \DB::table('payment_logs')->insert([
            'user_id' => $user->id ?? null,
            'organization_id' => $user->organization_id ?? null,
            'order_id' => $data['order_id'] ?? null,
            'payment_id' => $data['payment_id'] ?? null,
            'error_code' => $data['error_code'] ?? null,
            'error_description' => $data['error_description'] ?? null,
            'error_source' => $data['error_source'] ?? null,
            'error_step' => $data['error_step'] ?? null,
            'error_reason' => $data['error_reason'] ?? null,
            'metadata' => json_encode($data['metadata'] ?? []),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['ok' => true]);
    }
}
