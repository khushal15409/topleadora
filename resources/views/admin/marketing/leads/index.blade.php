@extends('layouts.admin')

@section('title', __('Marketing leads'))

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3 mb-4">
        <div>
            <h4 class="mb-1">{{ __('Marketing leads') }}</h4>
            <p class="mb-0 text-body-secondary">{{ __('Submissions from public landing forms (separate from CRM pipeline).') }}</p>
        </div>
        <a href="{{ route('admin.marketing.leads.export', request()->query()) }}" class="btn btn-primary">{{ __('Export CSV') }}</a>
    </div>

    <form method="get" class="card card-body mb-3 py-3">
        <div class="row g-2 align-items-end">
            <div class="col-md-3 col-lg-2">
                <label class="form-label small">{{ __('Service') }}</label>
                <select name="service_id" class="form-select form-select-sm">
                    <option value="">{{ __('All services') }}</option>
                    @foreach ($services as $s)
                        <option value="{{ $s->id }}" @selected(request('service_id') == $s->id)>{{ $s->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 col-lg-2">
                <label class="form-label small">{{ __('From date') }}</label>
                <input type="date" name="from" class="form-control form-control-sm" value="{{ request('from') }}">
            </div>
            <div class="col-md-3 col-lg-2">
                <label class="form-label small">{{ __('To date') }}</label>
                <input type="date" name="to" class="form-control form-control-sm" value="{{ request('to') }}">
            </div>
            <div class="col-md-6 col-lg-4">
                <label class="form-label small">{{ __('Search') }}</label>
                <input type="text" name="q" class="form-control form-control-sm" value="{{ request('q') }}" placeholder="{{ __('Name, email, phone, city…') }}">
            </div>
            <div class="col-md-6 col-lg-2">
                <button type="submit" class="btn btn-sm btn-primary w-100 mt-md-4">{{ __('Apply filters') }}</button>
            </div>
        </div>
    </form>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover table-sm mb-0">
                <thead>
                <tr>
                    <th>{{ __('Name') }}</th>
                    <th>{{ __('Phone') }}</th>
                    <th>{{ __('Email') }}</th>
                    <th>{{ __('Service') }}</th>
                    <th>{{ __('Country') }}</th>
                    <th>{{ __('City') }}</th>
                    <th>{{ __('Date') }}</th>
                    <th class="d-none d-xl-table-cell">{{ __('Source / UTM') }}</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($leads as $lead)
                    <tr>
                        <td class="fw-medium">{{ $lead->name }}</td>
                        <td class="small text-nowrap">{{ $lead->phone }}</td>
                        <td class="small">{{ $lead->email ?? '—' }}</td>
                        <td>{{ $lead->service?->name ?? '—' }}</td>
                        <td class="small"><span class="text-muted">{{ $lead->country_code }}</span> {{ $lead->country_name }}</td>
                        <td class="small text-muted">{{ $lead->city ?? '—' }}</td>
                        <td class="text-nowrap small text-muted">{{ $lead->created_at?->format('Y-m-d H:i') }}</td>
                        <td class="small d-none d-xl-table-cell">
                            <code class="small">{{ $lead->source_page ?? '—' }}</code>
                            @if ($lead->utm_source || $lead->utm_medium || $lead->utm_campaign)
                                <div class="text-muted small mt-1">
                                    @if ($lead->utm_source){{ $lead->utm_source }}@endif
                                    @if ($lead->utm_medium) / {{ $lead->utm_medium }}@endif
                                    @if ($lead->utm_campaign) · {{ $lead->utm_campaign }}@endif
                                </div>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-body-secondary">{{ __('No marketing leads yet.') }}</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if ($leads->hasPages())
            <div class="card-body">{{ $leads->links() }}</div>
        @endif
    </div>
@endsection
