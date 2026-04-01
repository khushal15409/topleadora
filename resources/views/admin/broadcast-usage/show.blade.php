@extends('layouts.admin')

@section('title', __('Broadcast usage').' — '.$organization->name)

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.broadcast-usage.index') }}" class="text-body-secondary small text-decoration-none">
            <i class="icon-base ri ri-arrow-left-s-line me-1"></i>{{ __('Back to broadcast usage') }}
        </a>
    </div>

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
        <div>
            <h4 class="mb-1">{{ $organization->name }}</h4>
            <p class="mb-0 text-body-secondary">{{ __('Broadcast history for this organization.') }}</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body">
            <form method="get" action="{{ route('admin.broadcast-usage.show', $organization) }}" class="row g-3 align-items-end">
                <div class="col-12 col-md-4">
                    <label class="form-label" for="from">{{ __('From') }}</label>
                    <input type="date" id="from" name="from" value="{{ request('from') }}" class="form-control">
                </div>
                <div class="col-12 col-md-4">
                    <label class="form-label" for="to">{{ __('To') }}</label>
                    <input type="date" id="to" name="to" value="{{ request('to') }}" class="form-control">
                </div>
                <div class="col-12 col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">{{ __('Apply') }}</button>
                    <a href="{{ route('admin.broadcast-usage.show', $organization) }}" class="btn btn-label-secondary w-100">{{ __('Reset') }}</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
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
                    @forelse ($broadcasts as $b)
                        <tr>
                            <td class="text-body-secondary">{{ $b->id }}</td>
                            <td>{{ $b->messagePreview(120) }}</td>
                            <td>{{ number_format($b->total_recipients) }}</td>
                            <td>{{ number_format($b->sent_count) }}</td>
                            <td class="small text-body-secondary" data-order="{{ $b->created_at?->timestamp ?? 0 }}">
                                {{ $b->created_at?->format('M j, Y H:i') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-body-secondary py-4">{{ __('No broadcasts in this range.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-top">
            {{ $broadcasts->links() }}
        </div>
    </div>
@endsection

