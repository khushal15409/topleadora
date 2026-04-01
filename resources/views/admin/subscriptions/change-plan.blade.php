@extends('layouts.admin')

@section('title', __('Change plan'))

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.subscriptions.index') }}" class="text-body-secondary small text-decoration-none">
            <i class="icon-base ri ri-arrow-left-s-line align-middle"></i>
            {{ __('Back to subscriptions') }}
        </a>
        <h4 class="mt-2 mb-1">{{ __('Change plan') }}</h4>
        <p class="mb-0 text-body-secondary">
            {{ __('Organization: :name — This assigns a new paid plan and starts a fresh 30-day window, same as tenant checkout.', ['name' => $organization->name]) }}
        </p>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header border-bottom">
                    <h5 class="mb-0">{{ __('Select plan') }}</h5>
                </div>
                <form method="post" action="{{ route('admin.subscriptions.change-plan.update', $organization) }}" class="card-body">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">{{ __('Current snapshot') }}</label>
                        <div class="small text-body-secondary">
                            {{ $row['plan_name'] }} ·
                            <span class="text-heading">{{ $row['status_label'] }}</span>
                            @if ($row['end_date'])
                                · {{ __('Ends :date', ['date' => $row['end_date']->format('M j, Y')]) }}
                            @endif
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="plan_id" class="form-label">{{ __('New plan') }}</label>
                        <select name="plan_id" id="plan_id" class="form-select @error('plan_id') is-invalid @enderror" required>
                            <option value="">{{ __('Choose…') }}</option>
                            @foreach ($plans as $plan)
                                <option value="{{ $plan->id }}" @selected((int) old('plan_id') === $plan->id)>
                                    {{ $plan->name }} —
                                    @if (strtoupper((string) $plan->currency) === 'INR')
                                        ₹
                                    @else
                                        {{ $plan->currency }}
                                    @endif
                                    {{ number_format((float) $plan->price_monthly, 0) }}/{{ __('mo') }}
                                </option>
                            @endforeach
                        </select>
                        @error('plan_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">{{ __('Apply plan') }}</button>
                        <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-label-secondary">{{ __('Cancel') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
