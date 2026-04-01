@extends('layouts.admin')

@section('title', __('Edit lead'))

@section('content')
    <div class="mb-4">
        <a href="{{ route('dashboard.leads.index') }}" class="text-body-secondary small text-decoration-none">
            <i class="icon-base ri ri-arrow-left-s-line me-1"></i>{{ __('Back to leads') }}
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-transparent border-0 pb-0 pt-4 px-4">
            <h5 class="mb-0">{{ __('Edit lead') }}</h5>
        </div>
        <div class="card-body p-4">
            <form method="post" action="{{ route('dashboard.leads.update', $lead) }}">
                @csrf
                @method('PUT')
                @include('dashboard.leads._form', [
                    'submitLabel' => __('Update lead'),
                ])
            </form>
        </div>
    </div>
@endsection
