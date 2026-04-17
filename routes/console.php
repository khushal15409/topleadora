<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Models\Payment;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('payments:reconcile-razorpay {--minutes=30 : Look back window for pending payments}', function () {
    $minutes = (int) $this->option('minutes');
    if ($minutes <= 0) {
        $minutes = 30;
    }

    $since = now()->subMinutes($minutes);

    $suspects = Payment::query()
        ->where('gateway', 'razorpay')
        ->where('status', Payment::STATUS_PENDING)
        ->whereNotNull('razorpay_order_id')
        ->where('created_at', '>=', $since)
        ->orderByDesc('id')
        ->limit(200)
        ->get(['id', 'organization_id', 'plan_id', 'amount', 'currency', 'razorpay_order_id', 'razorpay_payment_id', 'created_at']);

    $this->info('Pending Razorpay payments (window: last '.$minutes.' minutes): '.$suspects->count());
    if ($suspects->isEmpty()) {
        return 0;
    }

    $rows = $suspects->map(function ($p) {
        return [
            'id' => $p->id,
            'org' => $p->organization_id,
            'plan' => $p->plan_id,
            'amount' => (string) $p->amount.' '.strtoupper((string) $p->currency),
            'order_id' => $p->razorpay_order_id,
            'payment_id' => $p->razorpay_payment_id ?: '—',
            'created_at' => (string) $p->created_at,
        ];
    });

    $this->table(['id', 'org', 'plan', 'amount', 'order_id', 'payment_id', 'created_at'], $rows->toArray());
    $this->comment('Tip: captured payments should not remain pending; investigate webhook delivery / verify endpoint usage.');

    return 0;
})->purpose('List pending Razorpay payments for reconciliation');
