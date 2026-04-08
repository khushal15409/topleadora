@extends('layouts.admin')

@section('title', __('WhatsApp Broadcast'))

@section('content')
    <!-- Page Header -->
    <div class="md:flex block items-center justify-between mb-6 mt-[2rem] page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title text-[1.3125rem] font-medium text-defaulttextcolor mb-0">{{ __('WhatsApp Broadcast') }}
            </h5>
            <nav>
                <ol class="flex items-center whitespace-nowrap min-w-0">
                    <li class="text-[12px]">
                        <a class="flex items-center text-primary hover:text-primary" href="javascript:void(0);">
                            {{ __('Dashboard') }}
                            <i
                                class="ti ti-chevrons-right flex-shrink-0 mx-3 overflow-visible text-textmuted rtl:rotate-180"></i>
                        </a>
                    </li>
                    <li class="text-[12px]">
                        <a class="flex items-center text-textmuted" href="javascript:void(0);">
                            {{ __('Broadcast') }}
                        </a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header Close -->

    @if (session('success'))
        <div class="bg-success/10 text-success border border-success/20 p-4 rounded-md mb-4 flex justify-between items-center"
            role="alert">
            {{ session('success') }}
            <button type="button" class="text-success" data-bs-dismiss="alert" aria-label="Close">
                <i class="ri-close-line"></i>
            </button>
        </div>
    @endif

    <div class="grid grid-cols-12 gap-x-6">
        <!-- New Broadcast Form -->
        <div class="col-span-12 lg:col-span-4">
            <div class="box h-full">
                <div class="box-header border-b">
                    <h5 class="box-title font-semibold">{{ __('New Broadcast') }}</h5>
                    <p class="text-textmuted text-xs mt-1">{{ __('Send a massive WhatsApp outreach to your leads.') }}</p>
                </div>
                <div class="box-body">
                    <form method="post" action="{{ route('dashboard.broadcast.store') }}" id="broadcast-form"
                        class="space-y-6">
                        @csrf

                        <div class="flex items-center">
                            <input type="checkbox" name="send_to_all" value="1" id="send-to-all"
                                class="ti-form-checkbox h-4 w-4 text-primary border-gray-300 rounded focus:ring-primary">
                            <label for="send-to-all" class="ms-2 block text-sm font-medium text-defaulttextcolor">
                                {{ __('Send to ALL leads with phone numbers') }}
                            </label>
                        </div>

                        <div id="lead-select-wrap" class="transition-opacity">
                            <label class="block text-sm font-medium mb-2"
                                for="lead-ids">{{ __('Select Specific Leads') }}</label>
                            <select name="lead_ids[]" id="lead-ids"
                                class="ti-form-select min-h-[200px] @error('lead_ids') !border-danger @enderror" multiple>
                                @foreach ($leads as $lead)
                                    <option value="{{ $lead->id }}">{{ $lead->name }} — {{ $lead->phone }}</option>
                                @endforeach
                            </select>
                            @error('lead_ids')
                                <p class="text-danger text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-textmuted text-[11px] mt-2 italic">
                                {{ __('Hold Ctrl (Win) or Cmd (Mac) to select multiple.') }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2" for="message">{{ __('Message Content') }} <span
                                    class="text-danger">*</span></label>
                            <textarea name="message" id="message" rows="6"
                                class="ti-form-input @error('message') !border-danger @enderror" required
                                placeholder="{{ __('Hi {name}, we have a special offer for you...') }}">{{ old('message') }}</textarea>
                            @error('message')
                                <p class="text-danger text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" class="ti-btn ti-btn-primary w-full py-3">
                            <i class="ri-send-plane-2-line me-2"></i>{{ __('Dispatch Broadcast') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- History Section -->
        <div class="col-span-12 lg:col-span-8">
            <div class="box">
                <div class="box-header border-b flex justify-between items-center sm:flex-nowrap flex-wrap gap-2">
                    <div>
                        <h5 class="box-title font-semibold">{{ __('Broadcast History') }}</h5>
                        <p class="text-textmuted text-xs mt-1">
                            {{ __('Monitor the delivery status of your recent campaigns.') }}</p>
                    </div>
                    <div>
                        <form method="get" action="{{ route('dashboard.broadcast.index') }}" class="flex gap-2">
                            <input type="search" name="q" class="ti-form-input !py-2 !px-3 !text-xs !w-48"
                                value="{{ request('q') }}" placeholder="{{ __('Search campaigns...') }}">
                            <button type="submit" class="ti-btn ti-btn-light !mb-0 !py-2 !px-3">
                                <i class="ri-search-2-line"></i>
                            </button>
                        </form>
                    </div>
                </div>
                <div class="box-body !p-0">
                    @if ($history->isEmpty())
                        <div class="p-12 text-center text-textmuted">
                            <i class="ri-history-line text-4xl mb-2 block opacity-20"></i>
                            {{ __('No broadcasts found.') }}
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="ti-custom-table table-hover text-nowrap w-full">
                                <thead class="bg-gray-50 border-y dark:bg-black/10">
                                    <tr>
                                        <th scope="col" class="!py-3 !px-4">{{ __('ID') }}</th>
                                        <th scope="col" class="!py-3 !px-4">{{ __('Message Preview') }}</th>
                                        <th scope="col" class="!py-3 !px-4">{{ __('Recipients') }}</th>
                                        <th scope="col" class="!py-3 !px-4">{{ __('Sent') }}</th>
                                        <th scope="col" class="!py-3 !px-4">{{ __('Date') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($history as $row)
                                        <tr class="border-b last:border-0 hover:bg-gray-50/50 transition-colors h-14 text-sm">
                                            <td class="!px-4 font-bold text-textmuted">#{{ $row->id }}</td>
                                            <td class="!px-4">
                                                <div class="max-w-[300px] truncate" title="{{ $row->messagePreview(500) }}">
                                                    {{ $row->messagePreview(100) }}
                                                </div>
                                            </td>
                                            <td class="!px-4">
                                                <span
                                                    class="badge bg-gray-100 text-gray-800 rounded-full px-2">{{ number_format($row->total_recipients) }}</span>
                                            </td>
                                            <td class="!px-4 font-medium text-success">
                                                {{ number_format($row->sent_count) }}
                                            </td>
                                            <td class="!px-4 text-textmuted text-xs">
                                                {{ $row->created_at?->format('M j, Y') }}<br>
                                                <small>{{ $row->created_at?->format('H:i') }}</small>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
                @if ($history->hasPages())
                    <div class="box-footer p-4 border-t">
                        {{ $history->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('page-js')
    <script>
        (function () {
            const allCheckbox = document.getElementById('send-to-all');
            const wrap = document.getElementById('lead-select-wrap');
            const selectEl = document.getElementById('lead-ids');
            if (!allCheckbox || !wrap || !selectEl) return;

            function sync() {
                const isAll = allCheckbox.checked;
                selectEl.disabled = isAll;
                wrap.style.opacity = isAll ? '0.5' : '1';
                wrap.style.pointerEvents = isAll ? 'none' : 'auto';
            }

            allCheckbox.addEventListener('change', sync);
            sync();
        })();
    </script>
@endpush