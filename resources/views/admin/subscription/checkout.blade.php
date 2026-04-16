@extends('layouts.admin')

@section('title', 'Payment — '.$plan->name)

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.subscription.pricing') }}" class="text-body-secondary small text-decoration-none">
            <i class="icon-base ri ri-arrow-left-s-line align-middle"></i>
            Back to plans
        </a>
        <h4 class="mt-2 mb-1">Complete upgrade</h4>
        <p class="mb-0 text-body-secondary">Review your plan and complete payment to activate subscription.</p>
    </div>

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible mb-4" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header border-bottom">
                    <h5 class="mb-0">{{ $plan->name }}</h5>
                </div>
                <div class="card-body">
                    <p class="mb-2">
                        <span class="text-body-secondary">Amount due</span><br>
                        <span class="h4 mb-0">{{ $plan->currency }} {{ number_format((float) $plan->price_monthly, 2) }}</span>
                        <span class="text-body-secondary">/ month</span>
                    </p>
                    @php
                        $razorpayConfigured = (string) setting('razorpay_key', '') !== '' && (string) setting('razorpay_secret', '') !== '';
                    @endphp

                    @if ($razorpayConfigured)
                        <p class="text-body-secondary small mb-0">
                            You will be redirected to Razorpay secure checkout to complete your payment.
                        </p>
                        <button type="button" class="btn btn-primary w-100 mt-4" id="pay-now-btn">
                            Pay with Razorpay
                        </button>
                        <div class="small text-body-secondary mt-2 d-none" id="pay-status"></div>
                    @else
                        <p class="text-body-secondary small">
                            Razorpay is not configured yet. You can still use the demo flow to activate subscription for testing.
                        </p>
                        @if (! app()->environment('production') || isSuperAdmin())
                            <form method="post" action="{{ route('admin.subscription.activate', $plan) }}" class="mt-4">
                                @csrf
                                <button type="submit" class="btn btn-label-primary w-100">
                                    Demo activate
                                </button>
                            </form>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page-js')
    @php
        $razorpayConfigured = (string) setting('razorpay_key', '') !== '' && (string) setting('razorpay_secret', '') !== '';
    @endphp

    @if ($razorpayConfigured)
        <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
        <script>
            (function () {
                const btn = document.getElementById('pay-now-btn');
                const statusEl = document.getElementById('pay-status');
                if (!btn) return;

                const mapRazorpayError = (err) => {
                    const description = (err && (err.description || err.reason || err.message)) ? String(err.description || err.reason || err.message) : '';
                    const code = err && err.code ? String(err.code) : '';
                    const lower = description.toLowerCase();

                    if (lower.includes('international cards are not supported')) {
                        return 'International cards are not supported. Please use UPI, Net Banking, or an Indian debit/credit card.';
                    }
                    if (lower.includes('payment cancelled') || code === 'PAYMENT_CANCELLED') {
                        return 'Payment was cancelled. You can try again when you’re ready.';
                    }
                    if (lower.includes('insufficient') && lower.includes('fund')) {
                        return 'Payment failed due to insufficient funds. Please try a different payment method.';
                    }

                    return description ? ('Payment failed. ' + description) : 'Payment failed. Please try again or use a different payment method.';
                };

                const setStatus = (text, type) => {
                    if (!statusEl) return;
                    statusEl.classList.remove('d-none');
                    statusEl.classList.toggle('text-danger', type === 'error');
                    statusEl.classList.toggle('text-success', type === 'success');
                    statusEl.textContent = text;
                };

                const csrf = '{{ csrf_token() }}';
                const createUrl = '{{ route('admin.subscription.razorpay.order', $plan) }}';
                const verifyUrl = '{{ route('admin.subscription.razorpay.verify') }}';

                btn.addEventListener('click', async function () {
                    btn.setAttribute('disabled', 'disabled');
                    setStatus('Creating order...', 'info');

                    try {
                        const res = await fetch(createUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrf,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({})
                        });

                        const data = await res.json().catch(() => ({}));
                        if (!res.ok) {
                            throw new Error(data?.message || 'Unable to create order');
                        }

                        const options = {
                            key: data.key,
                            amount: data.amount,
                            currency: data.currency,
                            name: data.name,
                            description: data.description,
                            order_id: data.order_id,
                            prefill: data.prefill || {},
                            notes: data.notes || {},
                            handler: async function (response) {
                                setStatus('Verifying payment...', 'info');

                                const vr = await fetch(verifyUrl, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': csrf,
                                        'Accept': 'application/json'
                                    },
                                    body: JSON.stringify(response)
                                });

                                const vdata = await vr.json().catch(() => ({}));
                                if (!vr.ok) {
                                    throw new Error(vdata?.message || 'Verification failed');
                                }

                                setStatus('Payment successful. Activating...', 'success');
                                window.location.href = '{{ route('admin.dashboard') }}';
                            },
                            modal: {
                                ondismiss: function () {
                                    setStatus('Payment was cancelled. You can try again.', 'error');
                                    btn.removeAttribute('disabled');
                                }
                            }
                        };

                        const rzp = new Razorpay(options);
                        rzp.on('payment.failed', function (resp) {
                            setStatus(mapRazorpayError(resp?.error), 'error');
                            btn.removeAttribute('disabled');
                        });
                        rzp.open();
                    } catch (e) {
                        setStatus(e?.message || 'Something went wrong', 'error');
                        btn.removeAttribute('disabled');
                    }
                });
            })();
        </script>
    @endif
@endpush
