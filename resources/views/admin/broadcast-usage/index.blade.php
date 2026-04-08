@extends('gcc.layouts.app')

@section('title', __('Broadcast usage'))

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
        <div>
            <h4 class="mb-1">{{ __('Broadcast usage') }}</h4>
            <p class="mb-0 text-body-secondary">{{ __('WhatsApp broadcast totals per organization.') }}</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body">
            <form method="get" action="{{ route('admin.broadcast-usage.index') }}" class="row g-3 align-items-end">
                <div class="col-12 col-md-4">
                    <label class="form-label" for="q">{{ __('Search organization') }}</label>
                    <input type="search" id="q" name="q" value="{{ request('q') }}" class="form-control" placeholder="{{ __('Name…') }}">
                </div>
                <div class="col-12 col-md-3">
                    <label class="form-label" for="from">{{ __('From') }}</label>
                    <input type="date" id="from" name="from" value="{{ request('from') }}" class="form-control">
                </div>
                <div class="col-12 col-md-3">
                    <label class="form-label" for="to">{{ __('To') }}</label>
                    <input type="date" id="to" name="to" value="{{ request('to') }}" class="form-control">
                </div>
                <div class="col-12 col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">{{ __('Apply') }}</button>
                    <a href="{{ route('admin.broadcast-usage.index') }}" class="btn btn-label-secondary w-100">{{ __('Reset') }}</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>{{ __('Organization') }}</th>
                        <th>{{ __('Total broadcasts sent') }}</th>
                        <th>{{ __('Total messages sent') }}</th>
                        <th>{{ __('Last broadcast') }}</th>
                        <th class="text-end">{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orgs as $org)
                        <tr>
                            <td class="fw-medium">{{ $org->name }}</td>
                            <td>{{ number_format((int) ($org->total_broadcasts_sent ?? 0)) }}</td>
                            <td>{{ number_format((int) ($org->total_messages_sent ?? 0)) }}</td>
                            <td class="text-body-secondary small" data-order="{{ $org->last_broadcast_at?->timestamp ?? 0 }}">
                                {{ $org->last_broadcast_at ? \Illuminate\Support\Carbon::parse($org->last_broadcast_at)->format('M j, Y H:i') : '—' }}
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.broadcast-usage.show', $org) }}" class="btn btn-sm btn-label-primary">{{ __('View details') }}</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-body-secondary py-4">{{ __('No organizations found.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-top">
            {{ $orgs->links() }}
        </div>
    </div>
@endsection

