@if (!empty($showPlanExpiredBanner))
    <div class="alert alert-warning border-0 rounded-0 mb-0 d-flex flex-wrap align-items-center gap-2 py-3 px-4 wp-crm-plan-expired-banner" role="alert">
        <i class="icon-base ri ri-alarm-warning-line flex-shrink-0"></i>
        <span class="flex-grow-1 mb-0">{{ __('Your plan has expired. Please renew to continue using CRM.') }}</span>
        <a href="{{ route('admin.organization.plan') }}" class="btn btn-sm btn-dark flex-shrink-0">
            {{ __('View plans') }}
        </a>
    </div>
@endif
