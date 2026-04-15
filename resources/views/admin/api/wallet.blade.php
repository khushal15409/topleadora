@extends('layouts.admin')

@section('title', 'Wallet & Top-Up')

@section('content')
    <!-- Page Header -->
    <div class="md:flex block items-center justify-between mb-6 mt-[2rem] page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title text-[1.3125rem] font-medium text-defaulttextcolor mb-0">{{ __('Financial Overview') }}</h5>
            <nav>
                <ol class="flex items-center whitespace-nowrap min-w-0">
                    <li class="text-[12px]">
                        <a class="flex items-center text-primary hover:text-primary" href="{{ route('dashboard.api.overview') }}">
                            {{ __('API Client') }}
                            <i class="ti ti-chevrons-right flex-shrink-0 mx-3 overflow-visible text-textmuted rtl:rotate-180"></i>
                        </a>
                    </li>
                    <li class="text-[12px]">
                        <a class="flex items-center text-textmuted" href="javascript:void(0);">
                            {{ __('Wallet & Transactions') }}
                        </a>
                    </li>
                </ol>
            </nav>
        </div>
        <div class="flex xl:my-auto right-content align-items-center">
            <div class="pe-1 xl:mb-0">
                <button type="button" class="ti-btn ti-btn-warning-full text-white ti-btn-icon me-2 !mb-0" onclick="window.location.reload()" title="{{ __('Refresh') }}">
                    <i class="ri-refresh-line"></i>
                </button>
            </div>
            <div class="xl:mb-0">
                <a href="{{ route('dashboard.api.logs') }}" class="ti-btn ti-btn-info-full text-white !mb-0">
                    <i class="ri-pulse-line me-1"></i> View Usage Logs
                </a>
            </div>
        </div>
    </div>
    <!-- Page Header Close -->

    {{-- Flash messages --}}
    @if(session('wallet_success'))
        <div class="bg-success/10 text-success border border-success/20 p-4 rounded-md mb-6 flex justify-between items-center" role="alert">
            <div class="flex items-center gap-2">
                <i class="ri-checkbox-circle-fill text-lg"></i>
                <span class="font-bold">{{ session('wallet_success') }}</span>
            </div>
            <button type="button" class="text-success hover:text-success/80" onclick="this.parentElement.remove()">
                <i class="ri-close-line"></i>
            </button>
        </div>
    @endif

    @if(session('wallet_error'))
        <div class="bg-danger/10 text-danger border border-danger/20 p-4 rounded-md mb-6 flex justify-between items-center" role="alert">
            <div class="flex items-center gap-2">
                <i class="ri-error-warning-fill text-lg"></i>
                <span class="font-bold">{{ session('wallet_error') }}</span>
            </div>
            <button type="button" class="text-danger hover:text-danger/80" onclick="this.parentElement.remove()">
                <i class="ri-close-line"></i>
            </button>
        </div>
    @endif

    <div class="grid grid-cols-12 gap-x-6">

        {{-- LEFT: Balance + Top-Up Panel --}}
        <div class="xl:col-span-4 col-span-12">

            {{-- Balance Card --}}
            <div class="box overflow-hidden shadow-none border border-defaultborder/10 !bg-primary relative z-0 mb-6">
                <div class="absolute opacity-10" style="bottom: -20px; right: -20px; transform: scale(1.5); z-index: -1;">
                    <i class="ri-wallet-3-fill" style="font-size: 10rem;"></i>
                </div>
                <div class="box-body !p-6">
                    <div class="flex items-center justify-between mb-5">
                        <div>
                            <h6 class="mb-1 text-white/80 uppercase font-bold tracking-wider text-[11px]">{{ __('Available Balance') }}</h6>
                            <p class="mb-0 text-white/60 text-[10px]">{{ __('Organization Prepaid Wallet') }}</p>
                        </div>
                        <div class="avatar avatar-sm rounded-md bg-white/20 text-white ring-1 ring-white/30">
                            <i class="ri-bank-card-line"></i>
                        </div>
                    </div>

                    <h2 class="text-4xl font-bold mb-2 text-white" id="walletBalanceDisplay">
                        <span class="text-2xl text-white/70 align-top me-1">₹</span>{{ number_format($organization->wallet_balance, 2) }}
                    </h2>

                    <div class="mt-5 pt-4 border-t border-white/20">
                        <p class="mb-0 text-white/60 text-[10px] text-center">{{ __('Minimum top-up amount is ₹100.') }}</p>
                    </div>
                </div>
            </div>

            {{-- Top-Up Card --}}
            @if(!$razorpayConfigured)
            <div class="box shadow-none border border-warning/30 bg-warning/5">
                <div class="box-body !p-5 text-center">
                    <div class="avatar avatar-lg bg-warning/10 text-warning rounded-full mb-3 mx-auto shadow-none border border-warning/20">
                        <i class="ri-error-warning-line text-2xl"></i>
                    </div>
                    <h6 class="font-bold text-[14px] mb-1">{{ __('Payment Gateway Not Configured') }}</h6>
                    <p class="text-textmuted text-[12px] mb-0">{{ __('Razorpay API keys are missing. Please contact your Super Admin to configure them under Settings → Integrations.') }}</p>
                </div>
            </div>
            @else
            <div class="box shadow-none border border-defaultborder/10">
                <div class="box-header !border-b !border-defaultborder/10 flex items-center gap-3">
                    <div class="avatar avatar-sm rounded-sm bg-primary/10 text-primary">
                        <i class="ri-add-circle-line text-[16px]"></i>
                    </div>
                    <h4 class="box-title font-semibold">{{ __('Add Funds') }}</h4>
                </div>
                <div class="box-body">
                    <p class="text-textmuted text-[12px] mb-4">{{ __('Select an amount or enter a custom value to recharge your wallet.') }}</p>

                    {{-- Quick amount buttons --}}
                    <div class="grid grid-cols-3 gap-2 mb-4">
                        @foreach([100, 500, 1000, 2000, 5000, 10000] as $preset)
                            <button type="button"
                                class="quick-amount-btn border border-defaultborder text-defaulttextcolor text-[13px] font-bold py-2 px-3 rounded-md hover:bg-primary/10 hover:border-primary hover:text-primary transition-colors text-center"
                                data-amount="{{ $preset }}">
                                ₹{{ number_format($preset) }}
                            </button>
                        @endforeach
                    </div>

                    {{-- Custom amount input --}}
                    <div class="mb-4">
                        <label class="form-label text-[11px] font-bold text-textmuted uppercase tracking-wider" for="topUpAmount">{{ __('Custom Amount (₹)') }}</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0 text-textmuted font-bold">₹</span>
                            <input type="number"
                                class="form-control bg-light border-0 shadow-none text-[15px] font-bold"
                                id="topUpAmount"
                                placeholder="Enter amount"
                                min="100"
                                max="100000"
                                step="1">
                        </div>
                        <div class="p-3 mb-4 rounded-md bg-warning/10 border border-warning/20">
                            <p class="text-warning-600 text-[11px] font-semibold mb-1 flex items-center gap-2">
                                <i class="ri-error-warning-fill text-[14px]"></i>
                                {{ __('Domestic Payments Only') }}
                            </p>
                            <p class="text-textmuted text-[10px] leading-relaxed mb-0">
                                {{ __('⚠️ Only Indian debit/credit cards and UPI are supported. International cards will not work due to gateway restrictions.') }}
                            </p>
                        </div>

                        <div class="flex flex-col gap-1 mt-1">
                            <p class="text-textmuted text-[11px] mb-0">{{ __('Min: ₹100 — Max: ₹1,00,000') }}</p>
                            <div class="flex items-center gap-2 mt-2">
                                <span class="badge bg-success/10 text-success !text-[10px] py-1 px-2 border border-success/20 uppercase tracking-tighter">{{ __('Recommended') }}</span>
                                <p class="text-textmuted text-[10px] mb-0">{{ __('Use UPI for fastest processing and 100% success rate.') }}</p>
                            </div>
                        </div>
                    </div>

                    <button type="button"
                        id="payNowBtn"
                        class="ti-btn ti-btn-primary-full w-full !text-[14px] font-bold"
                        onclick="initiatePayment()">
                        <i class="ri-secure-payment-line me-2"></i> {{ __('Pay Securely via Razorpay') }}
                    </button>

                    <div class="flex items-center justify-center gap-2 mt-3">
                        <i class="ri-shield-check-line text-success text-[14px]"></i>
                        <span class="text-textmuted text-[11px]">{{ __('Payments secured by Razorpay. SSL encrypted.') }}</span>
                    </div>
                </div>
            </div>
            @endif
        </div>

        {{-- RIGHT: Transaction Ledger --}}
        <div class="xl:col-span-8 col-span-12">
            <div class="box shadow-none overflow-hidden h-full">
                <div class="box-header !border-b !border-defaultborder/10 flex justify-between items-center">
                    <div>
                        <h4 class="box-title font-semibold">{{ __('Transaction Ledger') }}</h4>
                        <p class="text-textmuted text-[0.7rem] mt-1 mb-0">{{ __('Complete chronological record of deposits and API usage deductions.') }}</p>
                    </div>
                    <span class="badge bg-light text-textmuted border border-defaultborder px-3 py-1 text-[11px]">
                        {{ number_format($transactions->total()) }} {{ __('Entries') }}
                    </span>
                </div>
                <div class="box-body !p-0">
                    <div class="table-responsive">
                        <table class="ti-custom-table table-hover text-nowrap w-full">
                            <thead class="bg-gray-100/50 dark:bg-black/20 border-b border-defaultborder/10">
                                <tr>
                                    <th scope="col" class="!py-3 !px-4 text-[11px] font-bold uppercase tracking-wider">{{ __('Type') }}</th>
                                    <th scope="col" class="!py-3 !px-4 text-[11px] font-bold uppercase tracking-wider">{{ __('Amount') }}</th>
                                    <th scope="col" class="!py-3 !px-4 text-[11px] font-bold uppercase tracking-wider">{{ __('Description') }}</th>
                                    <th scope="col" class="!py-3 !px-4 text-[11px] font-bold uppercase tracking-wider">{{ __('Status') }}</th>
                                    <th scope="col" class="!py-3 !px-4 text-[11px] font-bold uppercase tracking-wider text-end">{{ __('Date & Time') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transactions as $trx)
                                    <tr class="border-b last:border-0 hover:bg-gray-50/50 transition-colors h-14">
                                        <td class="!px-4">
                                            @if($trx->type === 'credit')
                                                <span class="badge bg-success/10 text-success rounded-full px-3 py-1 text-[10px] border border-success/20">
                                                    <i class="ri-arrow-left-down-line me-1"></i> {{ __('Deposit') }}
                                                </span>
                                            @else
                                                <span class="badge bg-danger/10 text-danger rounded-full px-3 py-1 text-[10px] border border-danger/20">
                                                    <i class="ri-arrow-right-up-line me-1"></i> {{ __('Usage') }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="!px-4">
                                            <span class="font-bold text-sm {{ $trx->type === 'credit' ? 'text-success' : 'text-defaulttextcolor' }}">
                                                {{ $trx->type === 'credit' ? '+' : '-' }} ₹{{ number_format($trx->amount, 2) }}
                                            </span>
                                        </td>
                                        <td class="!px-4">
                                            <div class="flex flex-col">
                                                <span class="font-bold text-[13px]">{{ $trx->description }}</span>
                                                @if($trx->reference_id)
                                                    <span class="text-textmuted text-[10px] font-mono truncate max-w-[180px]">{{ $trx->reference_id }}</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="!px-4">
                                            @if(($trx->status ?? 'success') === 'success')
                                                <span class="badge bg-success/10 text-success rounded-full px-2 py-1 text-[10px] border border-success/20">
                                                    <i class="ri-check-line me-1"></i> Success
                                                </span>
                                            @elseif(($trx->status ?? '') === 'pending')
                                                <span class="badge bg-warning/10 text-warning rounded-full px-2 py-1 text-[10px] border border-warning/20">
                                                    <i class="ri-time-line me-1"></i> Pending
                                                </span>
                                            @else
                                                <span class="badge bg-danger/10 text-danger rounded-full px-2 py-1 text-[10px] border border-danger/20">
                                                    <i class="ri-close-line me-1"></i> Failed
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-end !px-4 text-textmuted text-[12px]">
                                            <span class="block">{{ $trx->created_at->format('M d, Y') }}</span>
                                            <span class="block text-[10px] mt-[1px]">{{ $trx->created_at->format('h:i A') }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-textmuted py-12">
                                            <div class="flex flex-col items-center justify-center">
                                                <div class="avatar avatar-lg bg-light text-textmuted rounded-full mb-3 shadow-none border">
                                                    <i class="ri-exchange-dollar-line text-2xl"></i>
                                                </div>
                                                <h6 class="font-bold text-[14px] mb-1">{{ __('No transactions yet') }}</h6>
                                                <p class="text-[12px] mb-0">{{ __('Add funds to your wallet to start using API services.') }}</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($transactions->hasPages())
                    <div class="box-footer p-4 border-t border-defaultborder/10">
                        {{ $transactions->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
{{-- Razorpay Checkout SDK --}}
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script>
    const CSRF_TOKEN     = '{{ csrf_token() }}';
    const CREATE_ORDER   = '{{ route('dashboard.api.wallet.create-order') }}';
    const VERIFY_PAYMENT = '{{ route('dashboard.api.wallet.verify-payment') }}';
    const USER_NAME      = {!! json_encode(auth()->user()->name) !!};
    const USER_EMAIL     = {!! json_encode(auth()->user()->email) !!};

    // Quick-amount buttons
    document.querySelectorAll('.quick-amount-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.quick-amount-btn').forEach(b => {
                b.classList.remove('!bg-primary/10', '!border-primary', '!text-primary', 'ring-2', 'ring-primary/30');
            });
            this.classList.add('!bg-primary/10', '!border-primary', '!text-primary', 'ring-2', 'ring-primary/30');
            document.getElementById('topUpAmount').value = this.dataset.amount;
        });
    });

    async function initiatePayment() {
        const amountInput = document.getElementById('topUpAmount');
        const amount = parseFloat(amountInput.value);

        if (!amount || amount < 100 || amount > 100000) {
            showAlert('error', 'Please enter a valid amount between ₹100 and ₹1,00,000.');
            return;
        }

        const btn = document.getElementById('payNowBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="ri-loader-4-line animate-spin me-2"></i> Creating order…';

        try {
            const res = await fetch(CREATE_ORDER, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ amount }),
            });

            const data = await res.json();

            if (!res.ok) {
                showAlert('error', data.message || 'Failed to create payment order.');
                resetBtn();
                return;
            }

            openRazorpay(data, amount);

        } catch (err) {
            showAlert('error', 'Network error. Please check your connection and try again.');
            resetBtn();
        }
    }

    function openRazorpay(orderData, amount) {
        console.log('[Razorpay] Opening Checkout Popup. Policy Check: accelerometer, gyroscope, payment should be allowed.');

        if (typeof window.Razorpay === 'undefined') {
            console.error('[Razorpay] SDK not loaded! Check your internet connection or Content-Security-Policy.');
            showAlert('error', 'Payment gateway could not be loaded. Please refresh the page.');
            resetBtn();
            return;
        }

        const options = {
            "key": orderData.key,
            "amount": orderData.amount, 
            "currency": orderData.currency || "INR",
            "name": orderData.name || "API Wallet",
            "description": orderData.description || "Wallet Credits Top-up",
            "order_id": orderData.order_id,
            "config": {
                "display": {
                    "preferences": {
                        "method": "upi"
                    }
                }
            },
            "handler": async function (response) {
                console.log('[Razorpay] Payment captured successfully. Verification started.');
                await verifyPayment(response, amount);
            },
            "prefill": {
                "name": orderData.prefill?.name || USER_NAME,
                "email": orderData.prefill?.email || USER_EMAIL,
                "contact": orderData.prefill?.contact || "",
            },
            "notes": orderData.notes || {
                "user_id": "{{ auth()->id() }}",
                "org_id": "{{ $organization->id }}"
            },
            "theme": {
                "color": "#0162e8"
            },
            "modal": {
                "ondismiss": function () {
                    console.log('[Razorpay] Modal closed by user');
                    showAlert('info', 'Payment cancelled');
                    resetBtn();
                }
            }
        };

        const rzp = new window.Razorpay(options);
        
        rzp.on('payment.failed', async function (response) {
            console.error('[Razorpay] Payment Failed Error:', response.error);
            
            const error = response.error;
            let description = error.description || 'Reason unknown';
            
            // Log error to backend for admin diagnostics
            await logPaymentError(error, orderData.order_id);

            // Specific user guidance
            if (description.includes('International cards are not supported')) {
                alert("Use Indian cards or UPI. International cards are not supported.");
            } else {
                showAlert('error', 'Payment failed: ' + description);
            }

            resetBtn();
        });

        rzp.open();
    }

    async function logPaymentError(error, orderId) {
        try {
            await fetch('{{ route('admin.wallet.log-error') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    order_id: orderId,
                    payment_id: error.metadata?.payment_id || null,
                    error_code: error.code,
                    error_description: error.description,
                    error_source: error.source,
                    error_step: error.step,
                    error_reason: error.reason,
                    metadata: error.metadata || {}
                })
            });
        } catch (e) {
            console.error('[Diagnostic] Failed to log error to server', e);
        }
    }

    async function verifyPayment(response, amount) {
        const btn = document.getElementById('payNowBtn');
        btn.innerHTML = '<i class="ri-loader-4-line animate-spin me-2"></i> Verifying payment…';

        try {
            const res = await fetch(VERIFY_PAYMENT, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    razorpay_order_id:   response.razorpay_order_id,
                    razorpay_payment_id: response.razorpay_payment_id,
                    razorpay_signature:  response.razorpay_signature,
                }),
            });

            const data = await res.json();

            if (res.ok && data.ok) {
                showAlert('success', `₹${amount.toFixed(2)} added to your wallet successfully! New balance: ₹${data.new_balance}`);
                // Refresh balance display dynamically
                const balEl = document.getElementById('walletBalanceDisplay');
                if (balEl) balEl.innerHTML = `<span class="text-2xl text-white/70 align-top me-1">₹</span>${data.new_balance}`;
                // Reload the page after a short delay to show updated transactions
                setTimeout(() => window.location.reload(), 2500);
            } else {
                showAlert('error', data.message || 'Payment verification failed. Please contact support.');
                resetBtn();
            }
        } catch (err) {
            showAlert('error', 'Verification request failed. If money was deducted, please contact support immediately.');
            resetBtn();
        }
    }

    function resetBtn() {
        const btn = document.getElementById('payNowBtn');
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = '<i class="ri-secure-payment-line me-2"></i> Pay Securely via Razorpay';
        }
    }

    function showAlert(type, message) {
        const existing = document.getElementById('walletAlert');
        if (existing) existing.remove();

        const colors = type === 'success'
            ? 'bg-success/10 text-success border-success/20'
            : 'bg-danger/10 text-danger border-danger/20';
        const icon = type === 'success' ? 'ri-checkbox-circle-fill' : 'ri-error-warning-fill';

        const alert = document.createElement('div');
        alert.id = 'walletAlert';
        alert.className = `${colors} border p-4 rounded-md mb-5 flex justify-between items-center`;
        alert.innerHTML = `
            <div class="flex items-center gap-2">
                <i class="${icon} text-lg"></i>
                <span class="font-medium text-[14px]">${message}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="ms-4 opacity-70 hover:opacity-100"><i class="ri-close-line text-lg"></i></button>
        `;

        const header = document.querySelector('.page-header-breadcrumb');
        header?.insertAdjacentElement('afterend', alert);
        alert.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
</script>
@endpush