@extends('layouts.admin')

@section('title', __('Broadcast'))

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="mb-4">
        <h4 class="mb-1">{{ __('Broadcast') }}</h4>
        <p class="mb-0 text-body-secondary">{{ __('Send a WhatsApp message to selected leads. History is saved below.') }}</p>
    </div>

    <div class="row g-4">
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-transparent border-0 pt-4 px-4">
                    <h5 class="mb-0">{{ __('New broadcast') }}</h5>
                </div>
                <div class="card-body px-4 pb-4">
                    <form method="post" action="{{ route('dashboard.broadcast.store') }}" id="broadcast-form">
                        @csrf
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="send_to_all"
                                    value="1"
                                    id="send-to-all"
                                >
                                <label class="form-check-label" for="send-to-all">{{ __('Send to all leads with phone') }}</label>
                            </div>
                        </div>
                        <div class="mb-3" id="lead-select-wrap">
                            <label class="form-label" for="lead-ids">{{ __('Select leads') }}</label>
                            <select
                                name="lead_ids[]"
                                id="lead-ids"
                                class="form-select @error('lead_ids') is-invalid @enderror"
                                multiple
                                size="10"
                            >
                                @foreach ($leads as $lead)
                                    <option value="{{ $lead->id }}">{{ $lead->name }} — {{ $lead->phone }}</option>
                                @endforeach
                            </select>
                            @error('lead_ids')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="text-body-secondary">{{ __('Hold Ctrl/Cmd to select multiple.') }}</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="message">{{ __('Message') }} <span class="text-danger">*</span></label>
                            <textarea
                                name="message"
                                id="message"
                                rows="5"
                                class="form-control @error('message') is-invalid @enderror"
                                required
                                placeholder="{{ __('Your WhatsApp message…') }}"
                            >{{ old('message') }}</textarea>
                            @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="icon-base ri ri-send-plane-line me-1"></i>{{ __('Send') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-transparent border-0 pt-4 px-4 d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <h5 class="mb-0">{{ __('History') }}</h5>
                    <form method="get" action="{{ route('dashboard.broadcast.index') }}" class="d-flex gap-2">
                        <input
                            type="search"
                            name="q"
                            class="form-control form-control-sm"
                            value="{{ request('q') }}"
                            placeholder="{{ __('Search message…') }}"
                            style="min-width: 12rem;"
                        >
                        <button type="submit" class="btn btn-sm btn-label-secondary">{{ __('Search') }}</button>
                    </form>
                </div>
                <div class="card-body px-0 pt-0 pb-0">
                    @if ($history->isEmpty())
                        <p class="text-body-secondary px-4 py-4 mb-0">{{ __('No broadcasts yet.') }}</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>{{ __('ID') }}</th>
                                        <th>{{ __('Message') }}</th>
                                        <th>{{ __('Recipients') }}</th>
                                        <th>{{ __('Sent') }}</th>
                                        <th>{{ __('Date') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($history as $row)
                                        <tr>
                                            <td class="text-body-secondary">{{ $row->id }}</td>
                                            <td>{{ $row->messagePreview(100) }}</td>
                                            <td>{{ number_format($row->total_recipients) }}</td>
                                            <td>{{ number_format($row->sent_count) }}</td>
                                            <td class="small text-body-secondary">{{ $row->created_at?->format('M j, Y H:i') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="px-4 py-3 border-top">
                            {{ $history->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page-js')
    <script>
        (function () {
            const all = document.getElementById('send-to-all');
            const wrap = document.getElementById('lead-select-wrap');
            const sel = document.getElementById('lead-ids');
            if (!all || !wrap || !sel) return;
            function sync() {
                const on = all.checked;
                sel.disabled = on;
                wrap.classList.toggle('opacity-50', on);
            }
            all.addEventListener('change', sync);
            sync();
        })();
    </script>
@endpush
