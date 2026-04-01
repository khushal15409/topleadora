@extends('layouts.admin')

@section('title', __('Pipeline'))

@push('vendor-css')
    <style>
        .wp-crm-kanban-scroll {
            overflow-x: auto;
            padding-bottom: 0.5rem;
        }
        .wp-crm-kanban-col {
            min-width: 17rem;
        }
        .wp-crm-kanban-card {
            border-radius: 0.85rem;
            transition: transform 0.15s ease, box-shadow 0.15s ease;
            cursor: grab;
            background: linear-gradient(180deg, #fff 0%, #f8fafc 100%);
        }
        .wp-crm-kanban-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 0.5rem 1.25rem rgba(67, 89, 113, 0.12) !important;
        }
        .wp-crm-kanban-card.sortable-ghost {
            opacity: 0.55;
        }
        .wp-crm-kanban-col-inner {
            min-height: 200px;
        }
    </style>
@endpush

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
        <div>
            <h4 class="mb-1">{{ __('Pipeline') }}</h4>
            <p class="mb-0 text-body-secondary">{{ __('Drag cards between stages — updates save automatically.') }}</p>
        </div>
        <a href="{{ route('dashboard.leads.index') }}" class="btn btn-label-secondary btn-sm">{{ __('Table view') }}</a>
    </div>

    <div class="wp-crm-kanban-scroll">
        <div class="d-flex flex-nowrap gap-3">
            @foreach ($columns as $col)
                <div class="wp-crm-kanban-col flex-shrink-0">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-header bg-transparent border-0 pb-0 pt-3 px-3 d-flex justify-content-between align-items-center">
                            <span class="fw-semibold">{{ $col['label'] }}</span>
                            <span class="badge bg-label-secondary rounded-pill">{{ $col['leads']->count() }}</span>
                        </div>
                        <div class="card-body pt-2 px-3 pb-3">
                            <div
                                class="kanban-column wp-crm-kanban-col-inner"
                                data-stage="{{ $col['key'] }}"
                            >
                                @foreach ($col['leads'] as $lead)
                                    <div
                                        class="card border-0 shadow-sm mb-2 wp-crm-kanban-card kanban-card"
                                        data-lead-id="{{ $lead->id }}"
                                    >
                                        <div class="card-body p-3">
                                            <div class="fw-medium mb-1">{{ $lead->name }}</div>
                                            <div class="small text-body-secondary mb-1">{{ $lead->phone ?? '—' }}</div>
                                            @if ($lead->notes)
                                                <div class="small text-muted text-truncate" title="{{ \Illuminate\Support\Str::limit($lead->notes, 120) }}">
                                                    {{ \Illuminate\Support\Str::limit($lead->notes, 48) }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="modal fade" id="kanbanDetailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title" id="kanban-detail-title">{{ __('Lead') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="kanban-detail-body"></div>
                <div class="modal-footer border-0 pt-0" id="kanban-detail-footer"></div>
            </div>
        </div>
    </div>
@endsection

@push('vendor-js')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js" crossorigin="anonymous"></script>
@endpush

@push('page-js')
    <script>
        (function () {
            if (typeof Sortable === 'undefined' || typeof bootstrap === 'undefined') return;
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const detailModal = new bootstrap.Modal(document.getElementById('kanbanDetailModal'));

            document.querySelectorAll('.kanban-column').forEach(function (col) {
                Sortable.create(col, {
                    group: 'pipeline',
                    animation: 180,
                    delay: 120,
                    delayOnTouchOnly: true,
                    easing: 'cubic-bezier(0.25, 1, 0.5, 1)',
                    onEnd: function (evt) {
                        if (evt.from === evt.to && evt.oldIndex === evt.newIndex) return;
                        const card = evt.item;
                        const id = card.getAttribute('data-lead-id');
                        const stage = evt.to.closest('.kanban-column').getAttribute('data-stage');
                        fetch('/dashboard/leads/' + id + '/stage', {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': token,
                                Accept: 'application/json',
                            },
                            body: JSON.stringify({ status: stage }),
                        }).then(function (r) {
                            if (!r.ok) window.location.reload();
                        });
                    },
                });
            });

            document.querySelectorAll('.kanban-card').forEach(function (card) {
                card.addEventListener('click', function (e) {
                    const id = card.getAttribute('data-lead-id');
                    fetch('/dashboard/leads/' + id + '/summary', {
                        headers: { Accept: 'application/json' },
                    })
                        .then(function (r) {
                            return r.json();
                        })
                        .then(function (data) {
                            document.getElementById('kanban-detail-title').textContent = data.name;
                            const body = document.getElementById('kanban-detail-body');
                            body.textContent = '';
                            function addBlock(label, value) {
                                const p = document.createElement('p');
                                p.className = 'mb-2';
                                const s = document.createElement('strong');
                                s.textContent = label;
                                p.appendChild(s);
                                p.appendChild(document.createElement('br'));
                                p.appendChild(document.createTextNode(value || '—'));
                                body.appendChild(p);
                            }
                            addBlock('{{ __('Phone') }}', data.phone);
                            addBlock('{{ __('Status') }}', data.status_label);
                            addBlock('{{ __('Source') }}', data.source_label);
                            if (data.notes) {
                                const p4 = document.createElement('p');
                                p4.className = 'mb-0';
                                const strong = document.createElement('strong');
                                strong.textContent = '{{ __('Notes') }}';
                                p4.appendChild(strong);
                                p4.appendChild(document.createElement('br'));
                                const pre = document.createElement('pre');
                                pre.className = 'small mb-0 mt-1 text-body-secondary';
                                pre.style.whiteSpace = 'pre-wrap';
                                pre.textContent = data.notes;
                                p4.appendChild(pre);
                                body.appendChild(p4);
                            }
                            const foot = document.getElementById('kanban-detail-footer');
                            foot.textContent = '';
                            const a = document.createElement('a');
                            a.href = '/dashboard/leads/' + data.id + '/edit';
                            a.className = 'btn btn-sm btn-primary';
                            a.textContent = '{{ __('Edit lead') }}';
                            foot.appendChild(a);
                            detailModal.show();
                        });
                });
            });
        })();
    </script>
@endpush
