@extends('layouts.admin')

@section('title', __('Follow-ups'))

@push('vendor-css')
    <style>
        .wp-crm-follow-tab .nav-link {
            border-radius: 2rem;
            padding: 0.35rem 1rem;
        }
        .wp-crm-follow-table {
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
            <h4 class="mb-1">{{ __('Follow-ups') }}</h4>
            <p class="mb-0 text-body-secondary">{{ __('Stay on top of scheduled callbacks and meetings.') }}</p>
        </div>
        <a href="{{ route('dashboard.leads.index') }}" class="btn btn-label-secondary btn-sm">{{ __('All leads') }}</a>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body pb-0">
            <ul class="nav nav-pills wp-crm-follow-tab flex-wrap gap-2 mb-2">
                <li class="nav-item">
                    <a
                        class="nav-link {{ $tab === 'today' ? 'active' : '' }}"
                        href="{{ route('dashboard.followups.index', ['tab' => 'today']) }}"
                    >{{ __('Today') }}</a>
                </li>
                <li class="nav-item">
                    <a
                        class="nav-link {{ $tab === 'upcoming' ? 'active' : '' }}"
                        href="{{ route('dashboard.followups.index', ['tab' => 'upcoming']) }}"
                    >{{ __('Upcoming') }}</a>
                </li>
                <li class="nav-item">
                    <a
                        class="nav-link {{ $tab === 'completed' ? 'active' : '' }}"
                        href="{{ route('dashboard.followups.index', ['tab' => 'completed']) }}"
                    >{{ __('Completed') }}</a>
                </li>
            </ul>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 wp-crm-follow-table">
        @if ($rows->isEmpty())
            <div class="card-body text-body-secondary">{{ __('Nothing in this list.') }}</div>
        @else
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('Lead') }}</th>
                            <th>{{ __('Phone') }}</th>
                            <th>{{ __('Follow-up date') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th class="text-end">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rows as $lead)
                            <tr>
                                <td class="fw-medium">{{ $lead->name }}</td>
                                <td>
                                    @if ($lead->phone)
                                        <a href="tel:{{ preg_replace('/\s+/', '', $lead->phone) }}" class="text-decoration-none">{{ $lead->phone }}</a>
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="text-body-secondary small">
                                    @if ($tab === 'completed')
                                        {{ $lead->followup_completed_at?->format('M j, Y H:i') ?? '—' }}
                                    @else
                                        {{ $lead->next_followup_at?->format('M j, Y H:i') ?? '—' }}
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-label-primary rounded-pill">{{ $lead->statusLabel() }}</span>
                                </td>
                                <td class="text-end">
                                    @if ($tab !== 'completed')
                                        @if ($lead->phone)
                                            <a href="tel:{{ preg_replace('/\s+/', '', $lead->phone) }}" class="btn btn-sm btn-label-primary me-1">{{ __('Call') }}</a>
                                        @endif
                                        @can('update', $lead)
                                            <form method="post" action="{{ route('dashboard.followups.complete', $lead) }}" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-primary">{{ __('Done') }}</button>
                                            </form>
                                        @endcan
                                    @else
                                        <a href="{{ route('dashboard.leads.edit', $lead) }}" class="btn btn-sm btn-text-secondary">{{ __('Open') }}</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
