@extends('layouts.admin')

@section('title', __('CRM Pipeline'))

@section('content')
    <!-- Page Header -->
    <div class="md:flex block items-center justify-between mb-6 mt-[2rem] page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title text-[1.3125rem] font-medium text-defaulttextcolor mb-0">{{ __('CRM Pipeline') }}</h5>
            <nav>
                <ol class="flex items-center whitespace-nowrap min-w-0">
                    <li class="text-[12px]">
                        <a class="flex items-center text-primary hover:text-primary" href="javascript:void(0);">
                            {{ __('Dashboard') }}
                            <i class="ti ti-chevrons-right flex-shrink-0 mx-3 overflow-visible text-textmuted rtl:rotate-180"></i>
                        </a>
                    </li>
                    <li class="text-[12px]">
                        <a class="flex items-center text-textmuted" href="javascript:void(0);">
                            {{ __('Pipeline') }}
                        </a>
                    </li>
                </ol>
            </nav>
        </div>

        <div class="flex xl:my-auto right-content align-items-center">
            <a href="{{ route('dashboard.leads.index') }}" class="ti-btn ti-btn-light font-medium">
                <i class="ri-list-check me-1"></i>{{ __('Table View') }}
            </a>
        </div>
    </div>
    <!-- Page Header Close -->

    <div class="overflow-x-auto pb-4 scrollbar-sm">
        <div class="flex flex-nowrap gap-4 min-w-max px-1">
            @foreach ($columns as $col)
                <div class="w-[300px] flex-shrink-0">
                    <div class="box h-full bg-gray-50/50 dark:bg-black/10 border-dashed border-2 border-defaultborder/50 shadow-none">
                        <div class="box-header !border-b-0 flex justify-between items-center p-4">
                            <h4 class="box-title font-bold text-sm uppercase tracking-wider text-textmuted">{{ $col['label'] }}</h4>
                            <span class="badge bg-primary/10 text-primary rounded-full px-2 py-0.5 text-[10px]">{{ $col['leads']->count() }}</span>
                        </div>
                        <div class="box-body !p-3">
                            <div class="kanban-column space-y-3 min-h-[500px]" data-stage="{{ $col['key'] }}">
                                @foreach ($col['leads'] as $lead)
                                    <div class="box mb-0 shadow-sm border border-defaultborder/50 cursor-grab active:cursor-grabbing hover:border-primary/50 transition-all kanban-card group" data-lead-id="{{ $lead->id }}">
                                        <div class="box-body !p-4">
                                            <div class="flex justify-between items-start mb-2">
                                                <h6 class="font-bold text-sm text-defaulttextcolor group-hover:text-primary transition-colors">{{ $lead->name }}</h6>
                                                <i class="ri-more-2-fill text-textmuted opacity-0 group-hover:opacity-100 transition-opacity"></i>
                                            </div>
                                            <div class="space-y-2">
                                                <div class="flex items-center text-xs text-textmuted">
                                                    <i class="ri-phone-line me-1.5"></i>
                                                    {{ $lead->phone ?? '—' }}
                                                </div>
                                                @if ($lead->notes)
                                                    <div class="text-[11px] text-textmuted line-clamp-2 italic bg-white dark:bg-black/20 p-2 rounded border border-defaultborder/30">
                                                        {{ $lead->notes }}
                                                    </div>
                                                @endif
                                                <div class="flex justify-between items-center pt-2 mt-2 border-t border-defaultborder/30">
                                                    <span class="text-[10px] text-textmuted">{{ $lead->sourceLabel() }}</span>
                                                    <span class="avatar avatar-xs bg-primary/10 text-primary rounded-full text-[9px] font-bold">
                                                        {{ strtoupper(substr($lead->name, 0, 1)) }}
                                                    </span>
                                                </div>
                                            </div>
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

    <!-- Lead Detail Modal -->
    <div id="kanbanDetailModal" class="hs-overlay hidden w-full h-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto">
        <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto min-h-[calc(100%-3.5rem)] flex items-center">
            <div class="flex flex-col bg-white border shadow-sm rounded-xl dark:bg-gray-800 dark:border-gray-700 dark:shadow-slate-700/[.7] w-full">
                <div class="flex justify-between items-center py-3 px-4 border-b dark:border-gray-700">
                    <h3 class="font-bold text-gray-800 dark:text-white" id="kanban-detail-title">{{ __('Lead Details') }}</h3>
                    <button type="button" class="hs-dropdown-toggle inline-flex flex-shrink-0 justify-center items-center h-8 w-8 rounded-md text-gray-500 hover:text-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 focus:ring-offset-white transition-all text-sm dark:focus:ring-gray-700 dark:focus:ring-offset-gray-800" data-hs-overlay="#kanbanDetailModal">
                        <span class="sr-only">{{ __('Close') }}</span>
                        <i class="ri-close-line text-xl"></i>
                    </button>
                </div>
                <div class="p-6 overflow-y-auto" id="kanban-detail-body">
                    <!-- Loaded via JS -->
                </div>
                <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-gray-700" id="kanban-detail-footer">
                    <!-- Loaded via JS -->
                </div>
            </div>
        </div>
    </div>
@endsection

@push('vendor-js')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" crossorigin="anonymous"></script>
@endpush

@push('page-js')
    <script>
        (function () {
            if (typeof Sortable === 'undefined') return;
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const detailModalEl = document.getElementById('kanbanDetailModal');

            document.querySelectorAll('.kanban-column').forEach(function (col) {
                Sortable.create(col, {
                    group: 'pipeline',
                    animation: 180,
                    ghostClass: 'opacity-40',
                    dragClass: 'shadow-lg',
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
                        .then(r => r.json())
                        .then(data => {
                            document.getElementById('kanban-detail-title').textContent = data.name;
                            const body = document.getElementById('kanban-detail-body');
                            body.innerHTML = '';
                            
                            const addBlock = (label, value) => {
                                const div = document.createElement('div');
                                div.className = 'mb-4';
                                div.innerHTML = `<label class="block text-xs font-bold uppercase text-textmuted mb-1">${label}</label><div class="text-sm font-medium">${value || '—'}</div>`;
                                body.appendChild(div);
                            };
                            
                            addBlock('{{ __('Phone') }}', data.phone);
                            addBlock('{{ __('Status') }}', `<span class="badge bg-primary/10 text-primary rounded-full px-3">${data.status_label}</span>`);
                            addBlock('{{ __('Source') }}', data.source_label);
                            
                            if (data.notes) {
                                const div = document.createElement('div');
                                div.className = 'pt-4 border-t dark:border-gray-700';
                                div.innerHTML = `<label class="block text-xs font-bold uppercase text-textmuted mb-2">{{ __('Notes') }}</label><div class="p-4 bg-gray-50 dark:bg-black/10 rounded-md text-xs leading-relaxed whitespace-pre-wrap">${data.notes}</div>`;
                                body.appendChild(div);
                            }
                            
                            const foot = document.getElementById('kanban-detail-footer');
                            foot.innerHTML = `
                                <button type="button" class="ti-btn ti-btn-light" data-hs-overlay="#kanbanDetailModal">{{ __('Close') }}</button>
                                <a href="/dashboard/leads/${data.id}/edit" class="ti-btn ti-btn-primary">{{ __('Full Edit') }}</a>
                            `;
                            
                            if (window.HSOverlay) {
                                HSOverlay.open(detailModalEl);
                            }
                        });
                });
            });
        })();
    </script>
@endpush
