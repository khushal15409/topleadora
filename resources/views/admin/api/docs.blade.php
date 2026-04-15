@extends('layouts.admin')

@section('title', 'API Documentation')

@section('content')
    <!-- Page Header -->
    <div class="md:flex block items-center justify-between mb-6 mt-[2rem] page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title text-[1.3125rem] font-medium text-defaulttextcolor mb-0">{{ __('Developer Sandbox') }}
            </h5>
            <nav>
                <ol class="flex items-center whitespace-nowrap min-w-0">
                    <li class="text-[12px]">
                        <a class="flex items-center text-primary hover:text-primary"
                            href="{{ route('dashboard.api.overview') }}">
                            {{ __('API Client') }}
                            <i
                                class="ti ti-chevrons-right flex-shrink-0 mx-3 overflow-visible text-textmuted rtl:rotate-180"></i>
                        </a>
                    </li>
                    <li class="text-[12px]">
                        <a class="flex items-center text-textmuted" href="javascript:void(0);">
                            {{ __('Documentation') }}
                        </a>
                    </li>
                </ol>
            </nav>
        </div>
        <div class="flex xl:my-auto right-content align-items-center">
            <div class="xl:mb-0">
                <a href="{{ route('dashboard.api.keys.index') }}" class="ti-btn ti-btn-primary-full text-white !mb-0">
                    <i class="ri-key-2-line me-1"></i> Get API Keys
                </a>
            </div>
        </div>
    </div>
    <!-- Page Header Close -->

    <div class="grid grid-cols-12 gap-x-6 items-start">
        <!-- Navigation Sidebar -->
        <div class="xl:col-span-3 col-span-12 sticky top-[100px]">
            <div class="box shadow-none">
                <div class="box-header !border-b !border-defaultborder/10">
                    <h4 class="box-title font-semibold text-[13px] uppercase tracking-wider"><i
                            class="ri-book-3-line me-2 text-primary"></i> {{ __('Contents') }}</h4>
                </div>
                <div class="box-body !p-2">
                    <nav class="flex flex-col gap-1">
                        <a class="py-2 px-4 text-sm font-medium rounded-md hover:bg-light transition-colors flex items-center text-defaulttextcolor"
                            href="#overview">
                            <i class="ri-dashboard-line me-2 text-[15px] opacity-70"></i> {{ __('Overview') }}
                        </a>
                        <a class="py-2 px-4 text-sm font-medium rounded-md hover:bg-light transition-colors flex items-center text-defaulttextcolor"
                            href="#auth">
                            <i class="ri-shield-keyhole-line me-2 text-[15px] opacity-70"></i> {{ __('Authentication') }}
                        </a>

                        <hr class="my-2 border-defaultborder/10">
                        <span
                            class="text-textmuted text-[10px] font-bold uppercase tracking-wider px-4 mb-1">{{ __('Endpoints') }}</span>

                        <a class="py-2 px-4 text-sm font-medium rounded-md hover:bg-light transition-colors flex items-center text-defaulttextcolor flex items-center"
                            href="#otp">
                            <i class="ri-message-3-line me-2 text-[15px] opacity-70"></i> {{ __('Send OTP') }}
                        </a>
                        <a class="py-2 px-4 text-sm font-medium rounded-md hover:bg-light transition-colors flex items-center text-defaulttextcolor"
                            href="#whatsapp">
                            <i class="ri-whatsapp-line me-2 text-[15px] opacity-70"></i> {{ __('Send WhatsApp') }}
                        </a>

                        <hr class="my-2 border-defaultborder/10">
                        <span
                            class="text-textmuted text-[10px] font-bold uppercase tracking-wider px-4 mb-1">{{ __('Resources') }}</span>

                        <a class="py-2 px-4 text-sm font-medium rounded-md hover:bg-light transition-colors flex items-center text-defaulttextcolor"
                            href="#errors">
                            <i class="ri-error-warning-line me-2 text-[15px] opacity-70"></i> {{ __('Error Codes') }}
                        </a>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Documentation Content -->
        <div class="xl:col-span-9 col-span-12 space-y-6">

            <!-- Overview -->
            <div class="box shadow-none mb-0" id="overview">
                <div class="box-body md:p-8 !p-5">
                    <div class="flex items-center mb-4">
                        <div class="avatar avatar-md rounded-md bg-primary/10 text-primary me-3">
                            <i class="ri-book-open-line text-xl"></i>
                        </div>
                        <h2 class="text-2xl font-bold mb-0">{{ __('API Overview') }}</h2>
                    </div>
                    <p class="text-textmuted text-sm leading-relaxed mb-6">Welcome to our Developer API. Use our powerful,
                        RESTful endpoints to programmatically send OTPs and WhatsApp messages globally. All API requests
                        must be made over HTTPS. Responses are always formatted as JSON.</p>

                    <div
                        class="p-4 bg-light rounded-md border border-defaultborder flex flex-wrap gap-4 items-center justify-between">
                        <div>
                            <span class="text-textmuted text-[11px] font-bold uppercase tracking-wider block mb-1">Base
                                Production URL</span>
                            <span class="text-primary font-mono text-[15px] font-semibold">{{ url('/api') }}</span>
                        </div>
                        <button class="ti-btn ti-btn-light border !mb-0 shadow-sm"
                            onclick="copyText('{{ url('/api') }}', this)" title="Copy URL">
                            <i class="ri-file-copy-line text-textmuted"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Authentication -->
            <div class="box shadow-none mb-0" id="auth">
                <div class="box-body md:p-8 !p-5">
                    <h3 class="text-xl font-bold mb-3">{{ __('Authentication') }}</h3>
                    <p class="text-textmuted text-sm leading-relaxed mb-5">All endpoints require authentication using a
                        Bearer Token. Generate API keys from your Dashboard and include them in the
                        <code>Authorization</code> header of your HTTP requests.</p>

                    <div
                        class="rounded-md border border-defaultborder overflow-hidden ring-1 ring-black/5 dark:ring-white/5 shadow-sm">
                        <div class="flex justify-between items-center px-4 py-2 bg-[#1e293b]">
                            <span class="text-white/60 text-[11px] font-mono">HTTP Header</span>
                            <button
                                class="text-white/50 hover:text-white transition-colors text-[12px] flex items-center pr-1"
                                onclick="copyText('Authorization: Bearer YOUR_API_KEY', this)">
                                <i class="ri-file-copy-line me-1"></i> Copy
                            </button>
                        </div>
                        <div class="bg-[#0f172a] p-4 font-mono text-[13px] text-[#a5b4fc] overflow-x-auto">
                            <span class="text-[#38bdf8]">Authorization</span>: Bearer YOUR_API_KEY
                        </div>
                    </div>
                </div>
            </div>

            <!-- Send OTP -->
            <div class="box shadow-none mb-0" id="otp">
                <div
                    class="box-header !border-b !border-defaultborder/10 !p-5 flex flex-wrap gap-3 items-center justify-between bg-light/30">
                    <div class="flex items-center gap-3">
                        <span
                            class="badge bg-success/10 text-success border border-success/20 rounded px-2 py-1 text-[11px] font-bold">POST</span>
                        <h4 class="box-title font-mono font-bold text-[16px] text-defaulttextcolor m-0">/send-otp</h4>
                    </div>
                </div>
                <div class="box-body md:p-8 !p-5">
                    <p class="text-textmuted text-sm leading-relaxed mb-6">Send a One-Time Password to a given phone number.
                        Deducts standard OTP rate from your wallet balance upon success.</p>

                    <h6 class="font-bold text-[12px] uppercase tracking-wider mb-3 text-textmuted">Request Parameters</h6>
                    <div class="table-responsive border border-defaultborder rounded-md mb-6 shadow-sm">
                        <table class="ti-custom-table text-nowrap w-full">
                            <thead class="bg-gray-100/50 dark:bg-black/20">
                                <tr>
                                    <th class="!py-3 !px-4 text-[11px] font-bold uppercase">Parameter</th>
                                    <th class="!py-3 !px-4 text-[11px] font-bold uppercase">Type</th>
                                    <th class="!py-3 !px-4 text-[11px] font-bold uppercase">Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-b border-defaultborder/10">
                                    <td class="!px-4">
                                        <div
                                            class="font-mono text-defaulttextcolor text-sm font-semibold border-b border-dashed border-defaultborder inline-block pb-0.5">
                                            phone</div>
                                    </td>
                                    <td class="!px-4">
                                        <div class="flex items-center gap-2">
                                            <span
                                                class="font-mono text-[11px] text-primary bg-primary/10 px-2 py-0.5 rounded">string</span>
                                            <span
                                                class="badge bg-danger/10 text-danger rounded-full px-2 py-0.5 text-[9px] border border-danger/20 font-bold">Required</span>
                                        </div>
                                    </td>
                                    <td class="!px-4 text-textmuted text-sm whitespace-normal max-w-sm">Phone number in
                                        international format starting with '+' (e.g. +1234567890).</td>
                                </tr>
                                <tr>
                                    <td class="!px-4">
                                        <div
                                            class="font-mono text-defaulttextcolor text-sm font-semibold border-b border-dashed border-defaultborder inline-block pb-0.5">
                                            message</div>
                                    </td>
                                    <td class="!px-4">
                                        <div class="flex items-center gap-2">
                                            <span
                                                class="font-mono text-[11px] text-primary bg-primary/10 px-2 py-0.5 rounded">string</span>
                                            <span
                                                class="badge bg-danger/10 text-danger rounded-full px-2 py-0.5 text-[9px] border border-danger/20 font-bold">Required</span>
                                        </div>
                                    </td>
                                    <td class="!px-4 text-textmuted text-sm whitespace-normal max-w-sm">The exact text
                                        content of the message.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <h6 class="font-bold text-[13px] mb-0 flex items-center text-defaulttextcolor"><i
                                        class="ri-terminal-box-line me-1 text-textmuted"></i> cURL Request</h6>
                                <button class="text-textmuted hover:text-primary text-[11px] transition-colors"
                                    onclick="copyPreContent('curl-otp', this)">Copy snippet</button>
                            </div>
                            <div class="rounded-md border border-defaultborder overflow-hidden shadow-sm h-[200px]">
                                <div class="bg-[#0f172a] p-4 font-mono text-[12px] leading-relaxed text-[#f8fafc] h-full overflow-y-auto"
                                    id="curl-otp">
                                    <span class="text-[#4ade80]">curl</span> -X POST {{ url('/api/send-otp') }} \
                                    -H <span class="text-[#fbbf24]">"Authorization: Bearer YOUR_API_KEY"</span> \
                                    -H <span class="text-[#fbbf24]">"Content-Type: application/json"</span> \
                                    -d '{
                                    <span class="text-[#38bdf8]">"phone"</span>: <span
                                        class="text-[#fbbf24]">"+1234567890"</span>,
                                    <span class="text-[#38bdf8]">"message"</span>: <span class="text-[#fbbf24]">"Your OTP
                                        code is 123456."</span>
                                    }'
                                </div>
                            </div>
                        </div>
                        <div>
                            <h6 class="font-bold text-[13px] mb-2 flex items-center text-defaulttextcolor"><i
                                    class="ri-code-box-line me-1 text-textmuted"></i> JSON Response <span
                                    class="text-success text-[10px] ml-2 px-1.5 py-0.5 bg-success/10 rounded">200 OK</span>
                            </h6>
                            <div class="rounded-md border border-defaultborder overflow-hidden shadow-sm h-[200px]">
                                <div
                                    class="bg-[#0f172a] p-4 font-mono text-[12px] leading-relaxed text-[#a7f3d0] h-full overflow-y-auto">
                                    {
                                    <span class="text-[#38bdf8]">"success"</span>: <span class="text-[#60a5fa]">true</span>,
                                    <span class="text-[#38bdf8]">"message"</span>: <span class="text-[#fbbf24]">"Otp queued
                                        successfully."</span>,
                                    <span class="text-[#38bdf8]">"data"</span>: {
                                    <span class="text-[#38bdf8]">"provider_id"</span>: <span
                                        class="text-[#fbbf24]">"mock_otp_60d5f"</span>,
                                    <span class="text-[#38bdf8]">"status"</span>: <span
                                        class="text-[#fbbf24]">"delivered"</span>
                                    }
                                    }
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Send WA Endpoint -->
            <div class="box shadow-none mb-0" id="whatsapp">
                <div
                    class="box-header !border-b !border-defaultborder/10 !p-5 flex flex-wrap gap-3 items-center justify-between bg-light/30">
                    <div class="flex items-center gap-3">
                        <span
                            class="badge bg-success/10 text-success border border-success/20 rounded px-2 py-1 text-[11px] font-bold">POST</span>
                        <h4 class="box-title font-mono font-bold text-[16px] text-defaulttextcolor m-0">/send-whatsapp</h4>
                    </div>
                </div>
                <div class="box-body md:p-8 !p-5">
                    <p class="text-textmuted text-sm leading-relaxed mb-6">Deliver a plain text message directly via
                        WhatsApp Official API. Deducts WA rate from wallet balance.</p>

                    <h6 class="font-bold text-[12px] uppercase tracking-wider mb-3 text-textmuted">Request Parameters</h6>
                    <div class="table-responsive border border-defaultborder rounded-md mb-6 shadow-sm">
                        <table class="ti-custom-table text-nowrap w-full">
                            <thead class="bg-gray-100/50 dark:bg-black/20">
                                <tr>
                                    <th class="!py-3 !px-4 text-[11px] font-bold uppercase">Parameter</th>
                                    <th class="!py-3 !px-4 text-[11px] font-bold uppercase">Type</th>
                                    <th class="!py-3 !px-4 text-[11px] font-bold uppercase">Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-b border-defaultborder/10">
                                    <td class="!px-4">
                                        <div
                                            class="font-mono text-defaulttextcolor text-sm font-semibold border-b border-dashed border-defaultborder inline-block pb-0.5">
                                            phone</div>
                                    </td>
                                    <td class="!px-4">
                                        <div class="flex items-center gap-2">
                                            <span
                                                class="font-mono text-[11px] text-primary bg-primary/10 px-2 py-0.5 rounded">string</span>
                                            <span
                                                class="badge bg-danger/10 text-danger rounded-full px-2 py-0.5 text-[9px] border border-danger/20 font-bold">Required</span>
                                        </div>
                                    </td>
                                    <td class="!px-4 text-textmuted text-sm whitespace-normal max-w-sm">Phone number in
                                        international format.</td>
                                </tr>
                                <tr>
                                    <td class="!px-4">
                                        <div
                                            class="font-mono text-defaulttextcolor text-sm font-semibold border-b border-dashed border-defaultborder inline-block pb-0.5">
                                            message</div>
                                    </td>
                                    <td class="!px-4">
                                        <div class="flex items-center gap-2">
                                            <span
                                                class="font-mono text-[11px] text-primary bg-primary/10 px-2 py-0.5 rounded">string</span>
                                            <span
                                                class="badge bg-danger/10 text-danger rounded-full px-2 py-0.5 text-[9px] border border-danger/20 font-bold">Required</span>
                                        </div>
                                    </td>
                                    <td class="!px-4 text-textmuted text-sm whitespace-normal max-w-sm">The plain-text
                                        message wrapper.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <h6 class="font-bold text-[13px] mb-0 flex items-center text-defaulttextcolor"><i
                                        class="ri-terminal-box-line me-1 text-textmuted"></i> cURL Request</h6>
                                <button class="text-textmuted hover:text-primary text-[11px] transition-colors"
                                    onclick="copyPreContent('curl-wa', this)">Copy snippet</button>
                            </div>
                            <div class="rounded-md border border-defaultborder overflow-hidden shadow-sm h-[200px]">
                                <div class="bg-[#0f172a] p-4 font-mono text-[12px] leading-relaxed text-[#f8fafc] h-full overflow-y-auto"
                                    id="curl-wa">
                                    <span class="text-[#4ade80]">curl</span> -X POST {{ url('/api/send-whatsapp') }} \
                                    -H <span class="text-[#fbbf24]">"Authorization: Bearer YOUR_API_KEY"</span> \
                                    -H <span class="text-[#fbbf24]">"Content-Type: application/json"</span> \
                                    -d '{
                                    <span class="text-[#38bdf8]">"phone"</span>: <span
                                        class="text-[#fbbf24]">"+1234567890"</span>,
                                    <span class="text-[#38bdf8]">"message"</span>: <span class="text-[#fbbf24]">"Hello from
                                        API!"</span>
                                    }'
                                </div>
                            </div>
                        </div>
                        <div>
                            <h6 class="font-bold text-[13px] mb-2 flex items-center text-defaulttextcolor"><i
                                    class="ri-code-box-line me-1 text-textmuted"></i> JSON Response <span
                                    class="text-success text-[10px] ml-2 px-1.5 py-0.5 bg-success/10 rounded">200 OK</span>
                            </h6>
                            <div class="rounded-md border border-defaultborder overflow-hidden shadow-sm h-[200px]">
                                <div
                                    class="bg-[#0f172a] p-4 font-mono text-[12px] leading-relaxed text-[#a7f3d0] h-full overflow-y-auto">
                                    {
                                    <span class="text-[#38bdf8]">"success"</span>: <span class="text-[#60a5fa]">true</span>,
                                    <span class="text-[#38bdf8]">"message"</span>: <span class="text-[#fbbf24]">"Whatsapp
                                        queued successfully."</span>,
                                    <span class="text-[#38bdf8]">"data"</span>: {
                                    <span class="text-[#38bdf8]">"provider_id"</span>: <span
                                        class="text-[#fbbf24]">"mock_wa_ab3c4"</span>,
                                    <span class="text-[#38bdf8]">"status"</span>: <span
                                        class="text-[#fbbf24]">"delivered"</span>
                                    }
                                    }
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Errors -->
            <div class="box shadow-none mb-0" id="errors">
                <div class="box-header !border-b !border-defaultborder/10">
                    <h4 class="box-title font-semibold">{{ __('Error Codes Interpretation') }}</h4>
                </div>
                <div class="box-body !p-0">
                    <div class="table-responsive">
                        <table class="ti-custom-table table-hover text-nowrap w-full border-t-0">
                            <thead class="bg-gray-100/50 dark:bg-black/20">
                                <tr>
                                    <th class="!py-3 !px-4 text-[11px] font-bold uppercase tracking-wider">
                                        {{ __('HTTP Status') }}</th>
                                    <th class="!py-3 !px-4 text-[11px] font-bold uppercase tracking-wider">
                                        {{ __('Meaning') }}</th>
                                    <th class="!py-3 !px-4 text-[11px] font-bold uppercase tracking-wider w-[50%]">
                                        {{ __('Resolution') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-b border-defaultborder/10">
                                    <td class="!px-4">
                                        <span
                                            class="badge bg-danger/10 text-danger px-2 py-1 flex items-center w-fit border border-danger/20 font-mono text-[11px]">401
                                            Unauthorized</span>
                                    </td>
                                    <td class="!px-4 font-bold text-sm text-defaulttextcolor">Invalid Token</td>
                                    <td class="!px-4 text-textmuted text-sm whitespace-normal">The provided API key is
                                        missing, invalid, or revoked. Check the Authorization header.</td>
                                </tr>
                                <tr class="border-b border-defaultborder/10">
                                    <td class="!px-4">
                                        <span
                                            class="badge bg-warning/10 text-warning px-2 py-1 flex items-center w-fit border border-warning/20 font-mono text-[11px]">402
                                            Payment Required</span>
                                    </td>
                                    <td class="!px-4 font-bold text-sm text-defaulttextcolor">Insufficient Balance</td>
                                    <td class="!px-4 text-textmuted text-sm whitespace-normal">Your wallet balance is too
                                        low to process this request. Access Dashboard > Wallet to top up.</td>
                                </tr>
                                <tr class="border-b border-defaultborder/10">
                                    <td class="!px-4">
                                        <span
                                            class="badge bg-danger/10 text-danger px-2 py-1 flex items-center w-fit border border-danger/20 font-mono text-[11px]">403
                                            Forbidden</span>
                                    </td>
                                    <td class="!px-4 font-bold text-sm text-defaulttextcolor">Access Disabled</td>
                                    <td class="!px-4 text-textmuted text-sm whitespace-normal">Your organization's API
                                        access has been revoked or disabled locally.</td>
                                </tr>
                                <tr>
                                    <td class="!px-4">
                                        <span
                                            class="badge bg-gray-200 text-gray-700 dark:bg-black/40 dark:text-gray-300 border border-gray-300 dark:border-white/10 px-2 py-1 flex items-center w-fit font-mono text-[11px]">422
                                            Unprocessable</span>
                                    </td>
                                    <td class="!px-4 font-bold text-sm text-defaulttextcolor">Validation Error</td>
                                    <td class="!px-4 text-textmuted text-sm whitespace-normal">Missing required parameters
                                        (e.g. phone, message) or invalid parameter formatting. Read JSON body text.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function copyText(text, btn) {
            navigator.clipboard.writeText(text);
            let originalIcon = btn.innerHTML;
            btn.innerHTML = '<i class="ri-check-line text-success"></i>';
            setTimeout(() => { btn.innerHTML = originalIcon; }, 2000);
        }

        function copyPreContent(elementId, btn) {
            let text = document.getElementById(elementId).innerText;
            navigator.clipboard.writeText(text);
            let originalText = btn.innerHTML;
            btn.innerHTML = 'Copied!';
            btn.classList.add('text-success', 'font-bold');
            setTimeout(() => {
                btn.innerHTML = originalText;
                btn.classList.remove('text-success', 'font-bold');
            }, 2000);
        }
    </script>
@endpush