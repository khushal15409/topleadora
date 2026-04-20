@extends('layouts.admin')

@section('title', __('Contact Message') . ' #' . $contact->id)

@section('content')
    <!-- Page Header -->
    <div class="md:flex block items-center justify-between mb-6 mt-[2rem] page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title text-[1.3125rem] font-medium text-defaulttextcolor mb-0">
                {{ __('Contact Message') }} #{{ $contact->id }}
            </h5>
            <nav>
                <ol class="flex items-center whitespace-nowrap min-w-0">
                    <li class="text-[12px]">
                        <a class="flex items-center text-primary hover:text-primary" href="{{ route('admin.contacts.index') }}">
                            {{ __('Contact Messages') }}
                            <i class="ti ti-chevrons-right flex-shrink-0 mx-3 overflow-visible text-textmuted rtl:rotate-180"></i>
                        </a>
                    </li>
                    <li class="text-[12px]">
                        <span class="flex items-center text-textmuted">
                            #{{ $contact->id }}
                        </span>
                    </li>
                </ol>
            </nav>
        </div>

        <div class="flex items-center gap-2 mt-4 md:mt-0">
            @if (! $contact->is_read)
                <form action="{{ route('admin.contacts.mark-read', $contact) }}" method="post" class="inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="ti-btn ti-btn-primary">
                        <i class="ri-mail-open-line me-1"></i>{{ __('Mark as Read') }}
                    </button>
                </form>
            @endif

            <form action="{{ route('admin.contacts.destroy', $contact) }}" method="post" class="inline"
                data-confirm="{{ __('Delete message permanently?') }}">
                @csrf
                @method('DELETE')
                <button type="submit" class="ti-btn ti-btn-soft-danger">
                    <i class="ri-delete-bin-line me-1"></i>{{ __('Delete') }}
                </button>
            </form>

            <a href="{{ route('admin.contacts.index') }}" class="ti-btn ti-btn-light">
                {{ __('Back') }}
            </a>
        </div>
    </div>
    <!-- Page Header Close -->

    <div class="grid grid-cols-12 gap-x-6">
        <div class="col-span-12 lg:col-span-8">
            <div class="box">
                <div class="box-header !border-b-0">
                    <h4 class="box-title font-semibold">{{ __('Message') }}</h4>
                    <p class="text-textmuted text-xs mt-1">
                        {{ $contact->created_at?->format('M j, Y g:i A') ?? '—' }}
                    </p>
                </div>
                <div class="box-body">
                    <div class="space-y-4">
                        <div>
                            <div class="text-xs font-bold uppercase text-textmuted mb-1">{{ __('Subject') }}</div>
                            <div class="font-semibold">{{ $contact->subject ?: __('(No Subject)') }}</div>
                        </div>

                        <div class="pt-4 border-t dark:border-gray-700">
                            <div class="text-xs font-bold uppercase text-textmuted mb-2">{{ __('Message Body') }}</div>
                            <div class="p-4 bg-gray-50 dark:bg-black/10 rounded-md text-sm leading-relaxed whitespace-pre-wrap">
                                {{ $contact->message ?: '—' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-span-12 lg:col-span-4">
            <div class="box">
                <div class="box-header !border-b-0">
                    <h4 class="box-title font-semibold">{{ __('Sender') }}</h4>
                </div>
                <div class="box-body">
                    <div class="space-y-4">
                        <div class="grid grid-cols-12 gap-2">
                            <div class="col-span-4 text-xs font-bold uppercase text-textmuted">{{ __('Name') }}</div>
                            <div class="col-span-8 font-medium">{{ $contact->name ?: '—' }}</div>
                        </div>
                        <div class="grid grid-cols-12 gap-2">
                            <div class="col-span-4 text-xs font-bold uppercase text-textmuted">{{ __('Email') }}</div>
                            <div class="col-span-8">
                                @if ($contact->email)
                                    <a href="mailto:{{ $contact->email }}" class="text-primary hover:underline">
                                        {{ $contact->email }}
                                    </a>
                                @else
                                    —
                                @endif
                            </div>
                        </div>
                        <div class="grid grid-cols-12 gap-2">
                            <div class="col-span-4 text-xs font-bold uppercase text-textmuted">{{ __('Phone') }}</div>
                            <div class="col-span-8">{{ $contact->phone ?: '—' }}</div>
                        </div>
                        <div class="grid grid-cols-12 gap-2">
                            <div class="col-span-4 text-xs font-bold uppercase text-textmuted">{{ __('Status') }}</div>
                            <div class="col-span-8">
                                @if ($contact->is_read)
                                    <span class="badge bg-gray-100 text-gray-500 rounded-full">{{ __('Read') }}</span>
                                @else
                                    <span class="badge bg-warning/10 text-warning rounded-full">{{ __('New') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

