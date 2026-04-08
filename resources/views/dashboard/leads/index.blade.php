@extends('layouts.admin')

@section('title', __('Leads'))

@section('content')
    <!-- Page Header -->
    <div class="md:flex block items-center justify-between mb-6 mt-[2rem] page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title text-[1.3125rem] font-medium text-defaulttextcolor mb-0">{{ __('CRM Leads') }}</h5>
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
                            {{ __('Leads') }}
                        </a>
                    </li>
                </ol>
            </nav>
        </div>

        <div class="flex xl:my-auto right-content align-items-center">
            @can('create', \App\Models\Lead::class)
                <a href="{{ route('dashboard.leads.create') }}" class="ti-btn ti-btn-primary font-medium">
                    <i class="ri-add-line me-1"></i>{{ __('Add lead') }}
                </a>
            @endcan
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
        <!-- Filter Box -->
        <div class="col-span-12 mb-6">
            <div class="box">
                <div class="box-body">
                    <form method="get" action="{{ route('dashboard.leads.index') }}">
                        <div class="grid grid-cols-12 gap-4 items-end">
                            <div class="col-span-12 md:col-span-5">
                                <label class="block text-xs font-bold uppercase text-textmuted mb-2"
                                    for="q">{{ __('Search') }}</label>
                                <input type="search" name="q" id="q" value="{{ request('q') }}" class="ti-form-input"
                                    placeholder="{{ __('Name, phone or email') }}">
                            </div>
                            <div class="col-span-12 md:col-span-4">
                                <label class="block text-xs font-bold uppercase text-textmuted mb-2"
                                    for="filt-status">{{ __('Status') }}</label>
                                <select name="status" id="filt-status" class="ti-form-select">
                                    <option value="">{{ __('All Statuses') }}</option>
                                    @foreach ($statusOptions as $value => $label)
                                        <option value="{{ $value }}" @selected($statusFilter === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-span-12 md:col-span-3">
                                <div class="flex gap-2">
                                    <button type="submit" class="ti-btn ti-btn-primary flex-grow !mb-0">
                                        <i class="ri-filter-line me-1"></i>{{ __('Apply') }}
                                    </button>
                                    <a href="{{ route('dashboard.leads.index') }}" class="ti-btn ti-btn-light !mb-0">
                                        <i class="ri-refresh-line"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Leads Table -->
        <div class="col-span-12">
            <div class="box">
                <div class="box-header !border-b-0">
                    <h4 class="box-title font-semibold">{{ __('All Leads') }}</h4>
                    <p class="text-textmuted text-xs mt-1">
                        {{ __('Your organization’s leads — search and filter in one place.') }}</p>
                </div>
                <div class="box-body !p-0">
                    @if ($leads->isEmpty())
                        <div class="p-12 text-center text-textmuted">
                            <i class="ri-user-search-line text-4xl mb-2 block opacity-20"></i>
                            {{ __('No leads yet. Add one to get started.') }}
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="ti-custom-table table-hover text-nowrap w-full">
                                <thead class="bg-gray-50 border-y dark:bg-black/10">
                                    <tr>
                                        <th scope="col" class="!py-3 !px-4">{{ __('Lead Name') }}</th>
                                        <th scope="col" class="!py-3 !px-4">{{ __('Contact Info') }}</th>
                                        <th scope="col" class="!py-3 !px-4 d-none d-md-table-cell">{{ __('Niche') }}</th>
                                        <th scope="col" class="!py-3 !px-4">{{ __('Status') }}</th>
                                        <th scope="col" class="!py-3 !px-4">{{ __('Next Follow-up') }}</th>
                                        <th scope="col" class="!py-3 !px-4">{{ __('Assignee') }}</th>
                                        <th scope="col" class="!py-3 !px-4 text-end">{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($leads as $lead)
                                        <tr class="border-b last:border-0 hover:bg-gray-50/50 transition-colors h-14">
                                            <td class="!px-4 font-medium">
                                                {{ $lead->name }}
                                                <div class="text-[10px] text-textmuted mt-0.5">{{ $lead->sourceLabel() }}</div>
                                            </td>
                                            <td class="!px-4">
                                                <div class="flex flex-col text-sm">
                                                    <span class="font-medium">{{ $lead->phone ?? '—' }}</span>
                                                    <span
                                                        class="text-xs text-textmuted truncate max-w-[150px]">{{ $lead->email ?? '—' }}</span>
                                                </div>
                                            </td>
                                            <td class="!px-4 text-sm d-none d-md-table-cell">
                                                {{ $lead->niche ? ($nicheLabels[$lead->niche] ?? $lead->niche) : '—' }}
                                            </td>
                                            <td class="!px-4">
                                                <span
                                                    class="badge bg-primary/10 text-primary rounded-full px-3">{{ $lead->statusLabel() }}</span>
                                            </td>
                                            <td class="!px-4 text-sm text-textmuted">
                                                @if($lead->next_followup_at)
                                                    {{ $lead->next_followup_at->format('M j, Y') }}<br>
                                                    <small>{{ $lead->next_followup_at->format('H:i') }}</small>
                                                @else
                                                    —
                                                @endif
                                            </td>
                                            <td class="!px-4 text-sm">
                                                @if($lead->assignee)
                                                    <div class="flex items-center">
                                                        <span class="avatar avatar-xs bg-gray-100 rounded-full me-2">
                                                            {{ strtoupper(substr($lead->assignee->name, 0, 1)) }}
                                                        </span>
                                                        {{ $lead->assignee->name }}
                                                    </div>
                                                @else
                                                    —
                                                @endif
                                            </td>
                                            <td class="text-end !px-4">
                                                <div class="flex justify-end gap-2">
                                                    @can('update', $lead)
                                                        <button type="button"
                                                            class="ti-btn ti-btn-sm ti-btn-soft-primary !border-0 p-2 js-open-quick"
                                                            data-lead-id="{{ $lead->id }}" title="{{ __('Quick Update') }}">
                                                            <i class="ri-flashlight-line text-lg"></i>
                                                        </button>
                                                        <a href="{{ route('dashboard.leads.edit', $lead) }}"
                                                            class="ti-btn ti-btn-sm ti-btn-soft-secondary !border-0 p-2"
                                                            title="{{ __('Edit Detail') }}">
                                                            <i class="ri-pencil-line text-lg"></i>
                                                        </a>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
                @if ($leads->hasPages())
                    <div class="box-footer p-4 border-t">
                        {{ $leads->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    @can('viewAny', \App\Models\Lead::class)
        <!-- Quick Lead Modal -->
        <div id="quickLeadModal"
            class="hs-overlay hidden w-full h-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto">
            <div
                class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto min-h-[calc(100%-3.5rem)] flex items-center">
                <div
                    class="flex flex-col bg-white border shadow-sm rounded-xl dark:bg-gray-800 dark:border-gray-700 dark:shadow-slate-700/[.7] w-full">
                    <div class="flex justify-between items-center py-3 px-4 border-b dark:border-gray-700">
                        <h3 class="font-bold text-gray-800 dark:text-white">{{ __('Quick Update Lead') }}</h3>
                        <button type="button"
                            class="hs-dropdown-toggle inline-flex flex-shrink-0 justify-center items-center h-8 w-8 rounded-md text-gray-500 hover:text-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 focus:ring-offset-white transition-all text-sm dark:focus:ring-gray-700 dark:focus:ring-offset-gray-800"
                            data-hs-overlay="#quickLeadModal">
                            <span class="sr-only">{{ __('Close') }}</span>
                            <i class="ri-close-line text-xl"></i>
                        </button>
                    </div>
                    <div class="p-6 overflow-y-auto space-y-4">
                        <input type="hidden" id="quick-lead-id" value="">
                        <div>
                            <label class="block text-sm font-medium mb-2" for="quick-action">{{ __('Select Action') }}</label>
                            <select id="quick-action" class="ti-form-select">
                                <option value="status">{{ __('Change status') }}</option>
                                <option value="followup">{{ __('Add follow-up date') }}</option>
                                <option value="note">{{ __('Add note') }}</option>
                            </select>
                        </div>

                        <div id="quick-block-status" class="quick-block">
                            <label class="block text-sm font-medium mb-2" for="quick-status">{{ __('Status') }}</label>
                            <select id="quick-status" class="ti-form-select">
                                @foreach ($statusOptions as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div id="quick-block-followup" class="quick-block hidden">
                            <label class="block text-sm font-medium mb-2"
                                for="quick-followup">{{ __('Next follow-up') }}</label>
                            <input type="datetime-local" id="quick-followup" class="ti-form-input">
                        </div>

                        <div id="quick-block-note" class="quick-block hidden">
                            <label class="block text-sm font-medium mb-2" for="quick-note">{{ __('Note') }}</label>
                            <textarea id="quick-note" class="ti-form-input" rows="3"
                                placeholder="{{ __('Type your note here...') }}"></textarea>
                        </div>

                        <div id="quick-error" class="bg-danger/10 text-danger text-xs p-3 rounded hidden"></div>
                    </div>
                    <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-gray-700">
                        <button type="button" class="ti-btn ti-btn-light"
                            data-hs-overlay="#quickLeadModal">{{ __('Cancel') }}</button>
                        <button type="button" class="ti-btn ti-btn-primary" id="quick-submit">{{ __('Save Changes') }}</button>
                    </div>
                </div>
            </div>
        </div>
    @endcan
@endsection

@can('viewAny', \App\Models\Lead::class)
    @push('page-js')
        <script>
            (function () {
                const modalEl = document.getElementById('quickLeadModal');
                if (!modalEl) return;

                const idInput = document.getElementById('quick-lead-id');
                const actionSel = document.getElementById('quick-action');
                const blocks = {
                    status: document.getElementById('quick-block-status'),
                    followup: document.getElementById('quick-block-followup'),
                    note: document.getElementById('quick-block-note'),
                };
                const errEl = document.getElementById('quick-error');

                function toggleBlocks() {
                    const v = actionSel.value;
                    Object.keys(blocks).forEach(function (k) {
                        if (k === v) {
                            blocks[k].classList.remove('hidden');
                        } else {
                            blocks[k].classList.add('hidden');
                        }
                    });
                }
                actionSel.addEventListener('change', toggleBlocks);
                toggleBlocks();

                document.querySelectorAll('.js-open-quick').forEach(function (btn) {
                    btn.addEventListener('click', function () {
                        idInput.value = btn.getAttribute('data-lead-id');
                        errEl.classList.add('hidden');
                        if (window.HSOverlay) {
                            HSOverlay.open(modalEl);
                        }
                    });
                });

                document.getElementById('quick-submit').addEventListener('click', function () {
                    const id = idInput.value;
                    const action = actionSel.value;
                    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                    const body = { action: action };
                    if (action === 'status') body.status = document.getElementById('quick-status').value;
                    if (action === 'followup') body.next_followup_at = document.getElementById('quick-followup').value;
                    if (action === 'note') body.note = document.getElementById('quick-note').value;

                    errEl.classList.add('hidden');

                    fetch('/dashboard/leads/' + id + '/quick', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token,
                            Accept: 'application/json',
                        },
                        body: JSON.stringify(body),
                    })
                        .then(function (r) {
                            if (!r.ok) return r.json().then(function (j) {
                                throw new Error(j.message || 'Error');
                            });
                            return r.json();
                        })
                        .then(function () {
                            if (window.HSOverlay) HSOverlay.close(modalEl);
                            window.location.reload();
                        })
                        .catch(function (e) {
                            errEl.textContent = e.message || 'Request failed';
                            errEl.classList.remove('hidden');
                        });
                });
            })();
        </script>
    @endpush
@endcan