@extends('layouts.admin')

@section('title', __('Leads'))

@push('vendor-css')
    <style>
        .wp-crm-lead-card {
            border-radius: 1rem;
            box-shadow: 0 0.25rem 1rem rgba(67, 89, 113, 0.08);
        }
        .wp-crm-table-wrap {
            border-radius: 1rem;
            overflow: hidden;
        }
    </style>
@endpush

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
        <div>
            <h4 class="mb-1">{{ __('Leads') }}</h4>
            <p class="mb-0 text-body-secondary">{{ __('Your organization’s leads — search and filter in one place.') }}</p>
        </div>
        @can('create', \App\Models\Lead::class)
            <a href="{{ route('dashboard.leads.create') }}" class="btn btn-primary">
                <i class="icon-base ri ri-add-line me-1"></i>{{ __('Add lead') }}
            </a>
        @endcan
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4 wp-crm-lead-card">
        <div class="card-body">
            <form method="get" action="{{ route('dashboard.leads.index') }}" class="row g-3 align-items-end">
                <div class="col-12 col-md-4">
                    <label class="form-label small text-muted mb-1" for="q">{{ __('Search') }}</label>
                    <input
                        type="search"
                        name="q"
                        id="q"
                        value="{{ request('q') }}"
                        class="form-control"
                        placeholder="{{ __('Name, phone or email') }}"
                    >
                </div>
                <div class="col-12 col-md-3">
                    <label class="form-label small text-muted mb-1" for="filt-status">{{ __('Status') }}</label>
                    <select name="status" id="filt-status" class="form-select">
                        <option value="">{{ __('All') }}</option>
                        @foreach ($statusOptions as $value => $label)
                            <option value="{{ $value }}" @selected($statusFilter === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-5 d-flex flex-wrap gap-2">
                    <button type="submit" class="btn btn-primary">{{ __('Apply') }}</button>
                    <a href="{{ route('dashboard.leads.index') }}" class="btn btn-label-secondary">{{ __('Reset') }}</a>
                </div>
            </form>
        </div>
    </div>

    @if ($leads->isEmpty())
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body text-body-secondary">
                {{ __('No leads yet. Add one to get started.') }}
            </div>
        </div>
    @else
        <div class="card border-0 shadow-sm rounded-4 wp-crm-lead-card wp-crm-table-wrap">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('Name') }}</th>
                            <th class="d-none d-lg-table-cell">{{ __('Email') }}</th>
                            <th>{{ __('Phone') }}</th>
                            <th class="d-none d-md-table-cell">{{ __('Niche') }}</th>
                            <th>{{ __('Source') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Next follow-up') }}</th>
                            <th>{{ __('Assigned to') }}</th>
                            <th class="text-end">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($leads as $lead)
                            <tr>
                                <td class="fw-medium">{{ $lead->name }}</td>
                                <td class="d-none d-lg-table-cell small text-body-secondary">{{ $lead->email ?? '—' }}</td>
                                <td>{{ $lead->phone ?? '—' }}</td>
                                <td class="d-none d-md-table-cell small">{{ $lead->niche ? ($nicheLabels[$lead->niche] ?? $lead->niche) : '—' }}</td>
                                <td>{{ $lead->sourceLabel() }}</td>
                                <td>
                                    <span class="badge bg-label-primary rounded-pill">{{ $lead->statusLabel() }}</span>
                                </td>
                                <td class="text-body-secondary small">
                                    {{ $lead->next_followup_at?->format('M j, Y H:i') ?? '—' }}
                                </td>
                                <td>{{ $lead->assignee?->name ?? '—' }}</td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm">
                                        @can('update', $lead)
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-label-primary js-open-quick"
                                                data-lead-id="{{ $lead->id }}"
                                            >
                                                {{ __('Quick') }}
                                            </button>
                                            <a href="{{ route('dashboard.leads.edit', $lead) }}" class="btn btn-sm btn-text-secondary">
                                                {{ __('Edit') }}
                                            </a>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-3">
            {{ $leads->links() }}
        </div>
    @endif

    @can('viewAny', \App\Models\Lead::class)
        <div class="modal fade" id="quickLeadModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-4 border-0 shadow">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title">{{ __('Quick update') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body pt-2">
                        <input type="hidden" id="quick-lead-id" value="">
                        <div class="mb-3">
                            <label class="form-label small" for="quick-action">{{ __('Action') }}</label>
                            <select id="quick-action" class="form-select">
                                <option value="status">{{ __('Change status') }}</option>
                                <option value="followup">{{ __('Add follow-up date') }}</option>
                                <option value="note">{{ __('Add note') }}</option>
                            </select>
                        </div>
                        <div id="quick-block-status" class="quick-block">
                            <label class="form-label small" for="quick-status">{{ __('Status') }}</label>
                            <select id="quick-status" class="form-select">
                                @foreach ($statusOptions as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div id="quick-block-followup" class="quick-block d-none">
                            <label class="form-label small" for="quick-followup">{{ __('Next follow-up') }}</label>
                            <input type="datetime-local" id="quick-followup" class="form-control">
                        </div>
                        <div id="quick-block-note" class="quick-block d-none">
                            <label class="form-label small" for="quick-note">{{ __('Note') }}</label>
                            <textarea id="quick-note" class="form-control" rows="3"></textarea>
                        </div>
                        <div id="quick-error" class="alert alert-danger small d-none mt-2" role="alert"></div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="button" class="btn btn-primary" id="quick-submit">{{ __('Save') }}</button>
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
                if (!modalEl || typeof bootstrap === 'undefined') return;
                const modal = new bootstrap.Modal(modalEl);
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
                        blocks[k].classList.toggle('d-none', k !== v);
                    });
                }
                actionSel.addEventListener('change', toggleBlocks);
                toggleBlocks();

                document.querySelectorAll('.js-open-quick').forEach(function (btn) {
                    btn.addEventListener('click', function () {
                        idInput.value = btn.getAttribute('data-lead-id');
                        errEl.classList.add('d-none');
                        modal.show();
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
                    errEl.classList.add('d-none');
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
                            modal.hide();
                            window.location.reload();
                        })
                        .catch(function (e) {
                            errEl.textContent = e.message || 'Request failed';
                            errEl.classList.remove('d-none');
                        });
                });
            })();
        </script>
    @endpush
@endcan
