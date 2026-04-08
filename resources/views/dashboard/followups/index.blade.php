@extends('layouts.admin')

@section('title', __('Follow-ups'))

@section('content')
    <!-- Page Header -->
    <div class="md:flex block items-center justify-between mb-6 mt-[2rem] page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title text-[1.3125rem] font-medium text-defaulttextcolor mb-0">{{ __('Follow-ups') }}</h5>
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
                            {{ __('Follow-ups') }}
                        </a>
                    </li>
                </ol>
            </nav>
        </div>

        <div class="flex xl:my-auto right-content align-items-center">
            <a href="{{ route('dashboard.leads.index') }}" class="ti-btn ti-btn-light font-medium">
                <i class="ri-user-search-line me-1"></i>{{ __('View All Leads') }}
            </a>
        </div>
    </div>
    <!-- Page Header Close -->

    @if (session('success'))
        <div class="bg-success/10 text-success border border-success/20 p-4 rounded-md mb-4 flex justify-between items-center" role="alert">
            {{ session('success') }}
            <button type="button" class="text-success" data-bs-dismiss="alert" aria-label="Close">
                <i class="ri-close-line"></i>
            </button>
        </div>
    @endif

    <div class="grid grid-cols-12 gap-x-6">
        <!-- Tabs Section -->
        <div class="col-span-12 mb-6">
            <div class="box shadow-none border-0 !bg-transparent">
                <nav class="flex space-x-2 rtl:space-x-reverse" aria-label="Tabs" role="tablist">
                    <a href="{{ route('dashboard.followups.index', ['tab' => 'today']) }}" 
                       class="hs-tab-active:bg-primary hs-tab-active:text-white py-2 px-6 inline-flex items-center gap-2 text-sm font-medium text-center rounded-full transition-all {{ $tab === 'today' ? 'bg-primary text-white' : 'bg-white text-textmuted hover:text-primary' }}">
                        <i class="ri-calendar-check-line text-lg"></i>
                        {{ __('Today') }}
                    </a>
                    <a href="{{ route('dashboard.followups.index', ['tab' => 'upcoming']) }}" 
                       class="hs-tab-active:bg-primary hs-tab-active:text-white py-2 px-6 inline-flex items-center gap-2 text-sm font-medium text-center rounded-full transition-all {{ $tab === 'upcoming' ? 'bg-primary text-white' : 'bg-white text-textmuted hover:text-primary' }}">
                        <i class="ri-time-line text-lg"></i>
                        {{ __('Upcoming') }}
                    </a>
                    <a href="{{ route('dashboard.followups.index', ['tab' => 'completed']) }}" 
                       class="hs-tab-active:bg-primary hs-tab-active:text-white py-2 px-6 inline-flex items-center gap-2 text-sm font-medium text-center rounded-full transition-all {{ $tab === 'completed' ? 'bg-primary text-white' : 'bg-white text-textmuted hover:text-primary' }}">
                        <i class="ri-checkbox-circle-line text-lg"></i>
                        {{ __('Completed') }}
                    </a>
                </nav>
            </div>
        </div>

        <!-- Follow-ups Table -->
        <div class="col-span-12">
            <div class="box">
                <div class="box-header !border-b-0">
                    <h4 class="box-title font-semibold">{{ ucfirst($tab) }} {{ __('Queue') }}</h4>
                    <p class="text-textmuted text-xs mt-1">{{ __('Stay on top of scheduled callbacks and outreach tasks.') }}</p>
                </div>
                <div class="box-body !p-0">
                    @if ($rows->isEmpty())
                        <div class="p-12 text-center text-textmuted">
                            <i class="ri-calendar-2-line text-4xl mb-2 block opacity-20"></i>
                            {{ __('Nothing in this list.') }}
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="ti-custom-table table-hover text-nowrap w-full">
                                <thead class="bg-gray-50 border-y dark:bg-black/10">
                                    <tr>
                                        <th scope="col" class="!py-3 !px-4">{{ __('Lead') }}</th>
                                        <th scope="col" class="!py-3 !px-4">{{ __('Phone') }}</th>
                                        <th scope="col" class="!py-3 !px-4">{{ $tab === 'completed' ? __('Completed On') : __('Target Date') }}</th>
                                        <th scope="col" class="!py-3 !px-4">{{ __('Status') }}</th>
                                        <th scope="col" class="!py-3 !px-4 text-end">{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($rows as $lead)
                                        <tr class="border-b last:border-0 hover:bg-gray-50/50 transition-colors h-14">
                                            <td class="!px-4 font-medium">
                                                <div class="flex items-center">
                                                    <span class="avatar avatar-xs bg-primary/10 text-primary rounded-full me-2">
                                                        {{ strtoupper(substr($lead->name, 0, 1)) }}
                                                    </span>
                                                    {{ $lead->name }}
                                                </div>
                                            </td>
                                            <td class="!px-4">
                                                @if ($lead->phone)
                                                    <a href="tel:{{ preg_replace('/\s+/', '', $lead->phone) }}" class="text-primary hover:underline text-sm">
                                                        <i class="ri-phone-fill me-1"></i>{{ $lead->phone }}
                                                    </a>
                                                @else
                                                    <span class="text-textmuted text-xs">—</span>
                                                @endif
                                            </td>
                                            <td class="!px-4 text-sm text-textmuted">
                                                @php
                                                    $dt = $tab === 'completed' ? $lead->followup_completed_at : $lead->next_followup_at;
                                                @endphp
                                                @if($dt)
                                                    {{ $dt->format('M j, Y') }}<br>
                                                    <small>{{ $dt->format('H:i') }}</small>
                                                @else
                                                    —
                                                @endif
                                            </td>
                                            <td class="!px-4">
                                                <span class="badge bg-primary/10 text-primary rounded-full px-3 text-[10px]">{{ $lead->statusLabel() }}</span>
                                            </td>
                                            <td class="text-end !px-4">
                                                <div class="flex justify-end gap-2">
                                                    @if ($tab !== 'completed')
                                                        @if ($lead->phone)
                                                            <a href="tel:{{ preg_replace('/\s+/', '', $lead->phone) }}" class="ti-btn ti-btn-sm ti-btn-soft-primary !border-0 p-2" title="{{ __('Call Lead') }}">
                                                                <i class="ri-phone-line text-lg"></i>
                                                            </a>
                                                        @endif
                                                        @can('update', $lead)
                                                            <form method="post" action="{{ route('dashboard.followups.complete', $lead) }}" class="inline">
                                                                @csrf
                                                                <button type="submit" class="ti-btn ti-btn-sm ti-btn-primary px-4">
                                                                    {{ __('Done') }}
                                                                </button>
                                                            </form>
                                                        @endcan
                                                    @else
                                                        <a href="{{ route('dashboard.leads.edit', $lead) }}" class="ti-btn ti-btn-sm ti-btn-soft-secondary !border-0 p-2" title="{{ __('View Details') }}">
                                                            <i class="ri-external-link-line text-lg"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
