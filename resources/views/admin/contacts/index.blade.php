@extends('layouts.admin')

@section('title', __('Contact Messages'))

@section('content')
    <div class="hidden" data-admin-contacts-base="{{ route('admin.contacts.index') }}" aria-hidden="true"></div>

    <!-- Page Header -->
    <div class="md:flex block items-center justify-between mb-6 mt-[2rem] page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title text-[1.3125rem] font-medium text-defaulttextcolor mb-0">{{ __('Contact Messages') }}</h5>
            <nav>
                <ol class="flex items-center whitespace-nowrap min-w-0">
                    <li class="text-[12px]">
                        <a class="flex items-center text-primary hover:text-primary" href="javascript:void(0);">
                            {{ __('Admin') }}
                            <i
                                class="ti ti-chevrons-right flex-shrink-0 mx-3 overflow-visible text-textmuted rtl:rotate-180"></i>
                        </a>
                    </li>
                    <li class="text-[12px]">
                        <a class="flex items-center text-textmuted" href="javascript:void(0);">
                            {{ __('Contact Messages') }}
                        </a>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header Close -->

    @if (session('success'))
        <div class="bg-success/10 text-success border border-success/20 p-4 rounded-md mb-4 flex justify-between items-center"
            role="alert">
            {{ session('success') }}
            <button type="button" class="text-success" data-bs-dismiss="alert" aria-label="Close">
                <i class="ri-close-line"></i>
            </button>
        </div>
    @endif

    <div class="grid grid-cols-12 gap-x-6">
        <div class="col-span-12">
            <div class="box">
                <div class="box-header !border-b-0">
                    <h4 class="box-title font-semibold">{{ __('Inquiries') }}</h4>
                    <p class="text-textmuted text-xs mt-1">{{ __('Messages submitted from the website contact forms.') }}
                    </p>
                </div>
                <div class="box-body !p-0">
                    @if ($contacts->isEmpty())
                        <div class="p-12 text-center text-textmuted">
                            <i class="ri-mail-line text-4xl mb-2 block opacity-20"></i>
                            {{ __('No contact messages yet.') }}
                        </div>
                    @else
                        <div class="table-responsive p-4">
                            <table id="dt-contacts" class="ti-custom-table table-hover text-nowrap w-full datatable" data-disable-last-sort="1">
                                <thead class="bg-gray-50 border-y dark:bg-black/10">
                                    <tr>
                                        <th scope="col" class="!py-3 !px-4">{{ __('ID') }}</th>
                                        <th scope="col" class="!py-3 !px-4">{{ __('Sender') }}</th>
                                        <th scope="col" class="!py-3 !px-4">{{ __('Contact Info') }}</th>
                                        <th scope="col" class="!py-3 !px-4">{{ __('Subject & Message') }}</th>
                                        <th scope="col" class="!py-3 !px-4">{{ __('Date') }}</th>
                                        <th scope="col" class="!py-3 !px-4">{{ __('Status') }}</th>
                                        <th scope="col" class="!py-3 !px-4 text-end">{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($contacts as $c)
                                                            @php
                                                                $payload = [
                                                                    'id' => $c->id,
                                                                    'name' => $c->name,
                                                                    'email' => $c->email,
                                                                    'phone' => $c->phone,
                                                                    'subject' => $c->subject,
                                                                    'message' => $c->message,
                                                                    'created_at' => $c->created_at?->toIso8601String(),
                                                                    'is_read' => $c->is_read,
                                                                ];
                                                                $json = json_encode($payload, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE);
                                                            @endphp
                                         <tr
                                                                class="border-b last:border-0 transition-colors h-14 {{ $c->is_read ? 'hover:bg-gray-50/50' : 'bg-primary/5 hover:bg-primary/10' }}">
                                                                <td class="!px-4 text-xs font-medium text-textmuted" data-order="{{ $c->id }}">
                                                                    #{{ $c->id }}</td>
                                                                <td class="!px-4 font-medium">{{ $c->name }}</td>
                                                                <td class="!px-4">
                                                                    <div class="flex flex-col">
                                                                        <span class="text-sm font-medium">{{ $c->email }}</span>
                                                                        @if($c->phone)<span class="text-xs text-textmuted">{{ $c->phone }}</span>@endif
                                                                    </div>
                                                                </td>
                                                                <td class="!px-4">
                                                                    <div class="flex flex-col max-w-[250px]">
                                                                        <span
                                                                            class="text-sm font-medium truncate">{{ $c->subject ?? __('(No Subject)') }}</span>
                                                                        <span
                                                                            class="text-xs text-textmuted truncate">{{ \Illuminate\Support\Str::limit($c->message, 60) }}</span>
                                                                    </div>
                                                                </td>
                                                                <td class="!px-4 text-sm text-textmuted"
                                                                    data-order="{{ $c->created_at?->timestamp ?? 0 }}">
                                                                    {{ $c->created_at?->format('M j, Y') }}
                                                                </td>
                                                                <td class="!px-4 text-sm">
                                                                    @if ($c->is_read)
                                                                        <span class="badge bg-gray-100 text-gray-500 rounded-full">{{ __('Read') }}</span>
                                                                    @else
                                                                        <span class="badge bg-warning/10 text-warning rounded-full">{{ __('New') }}</span>
                                                                    @endif
                                                                </td>
                                                                <td class="text-end !px-4">
                                                                    <div class="flex justify-end gap-2 text-sm">
                                                                        <button type="button"
                                                                            class="ti-btn ti-btn-sm ti-btn-soft-primary !border-0 p-2 js-contact-view"
                                                                            title="{{ __('View Message') }}" data-contact="{{ e($json) }}">
                                                                            <i class="ri-eye-line text-lg"></i>
                                                                        </button>
                                                                        @if (!$c->is_read)
                                                                            <form action="{{ route('admin.contacts.mark-read', $c) }}" method="post"
                                                                                class="inline">
                                                                                @csrf
                                                                                @method('PATCH')
                                                                                <button type="submit"
                                                                                    class="ti-btn ti-btn-sm ti-btn-soft-secondary !border-0 p-2"
                                                                                    title="{{ __('Mark as Read') }}">
                                                                                    <i class="ri-mail-open-line text-lg"></i>
                                                                                </button>
                                                                            </form>
                                                                        @endif
                                                                        <form action="{{ route('admin.contacts.destroy', $c) }}" method="post"
                                                                            class="inline"
                                                                            data-confirm="{{ __('Delete message permanently?') }}">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="submit"
                                                                                class="ti-btn ti-btn-sm ti-btn-soft-danger !border-0 p-2"
                                                                                title="{{ __('Delete') }}">
                                                                                <i class="ri-delete-bin-line text-lg"></i>
                                                                            </button>
                                                                        </form>
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

    <!-- Message View Modal -->
    <div id="contactMessageModal"
        class="hs-overlay hidden w-full h-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto">
        <div
            class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto min-h-[calc(100%-3.5rem)] flex items-center">
            <div
                class="flex flex-col bg-white border shadow-sm rounded-xl dark:bg-gray-800 dark:border-gray-700 dark:shadow-slate-700/[.7] w-full">
                <div class="flex justify-between items-center py-3 px-4 border-b dark:border-gray-700">
                    <h3 class="font-bold text-gray-800 dark:text-white">{{ __('Message Details') }}</h3>
                    <button type="button"
                        class="hs-dropdown-toggle inline-flex flex-shrink-0 justify-center items-center h-8 w-8 rounded-md text-gray-500 hover:text-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 focus:ring-offset-white transition-all text-sm dark:focus:ring-gray-700 dark:focus:ring-offset-gray-800"
                        data-hs-overlay="#contactMessageModal">
                        <span class="sr-only">{{ __('Close') }}</span>
                        <i class="ri-close-line text-xl"></i>
                    </button>
                </div>
                <div class="p-6 overflow-y-auto">
                    <div class="space-y-4">
                        <div class="grid grid-cols-12 gap-2">
                            <div class="col-span-4 text-xs font-bold uppercase text-textmuted">{{ __('From') }}</div>
                            <div class="col-span-8 font-medium" id="cm-name"></div>
                        </div>
                        <div class="grid grid-cols-12 gap-2">
                            <div class="col-span-4 text-xs font-bold uppercase text-textmuted">{{ __('Email') }}</div>
                            <div class="col-span-8"><a href="#" id="cm-email-link" class="text-primary hover:underline"></a>
                            </div>
                        </div>
                        <div class="grid grid-cols-12 gap-2">
                            <div class="col-span-4 text-xs font-bold uppercase text-textmuted">{{ __('Phone') }}</div>
                            <div class="col-span-8" id="cm-phone"></div>
                        </div>
                        <div class="grid grid-cols-12 gap-2">
                            <div class="col-span-4 text-xs font-bold uppercase text-textmuted">{{ __('Subject') }}</div>
                            <div class="col-span-8 font-semibold" id="cm-subject"></div>
                        </div>
                        <div class="grid grid-cols-12 gap-2">
                            <div class="col-span-4 text-xs font-bold uppercase text-textmuted">{{ __('Date') }}</div>
                            <div class="col-span-8 text-sm" id="cm-date"></div>
                        </div>
                        <div class="pt-4 border-t dark:border-gray-700">
                            <div class="text-xs font-bold uppercase text-textmuted mb-2">{{ __('Message Body') }}</div>
                            <div class="p-4 bg-gray-50 dark:bg-black/10 rounded-md text-sm leading-relaxed whitespace-pre-wrap"
                                id="cm-message"></div>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-gray-700">
                    <form id="contactMarkReadForm" method="post" class="hidden me-auto">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="ti-btn ti-btn-primary"
                            id="contactMarkReadSubmit">{{ __('Mark as Read') }}</button>
                    </form>
                    <button type="button" class="ti-btn ti-btn-light"
                        data-hs-overlay="#contactMessageModal">{{ __('Close') }}</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page-js')
    <script>
        (function () {
            const modalEl = document.getElementById('contactMessageModal');
            const baseEl = document.querySelector('[data-admin-contacts-base]');
            if (!modalEl || !baseEl) return;

            const markReadForm = document.getElementById('contactMarkReadForm');
            const base = (baseEl.getAttribute('data-admin-contacts-base') || '').replace(/\/$/, '');

            function setText(id, text) {
                const el = document.getElementById(id);
                if (el) el.textContent = text || '—';
            }

            document.querySelectorAll('.js-contact-view').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    let data;
                    try {
                        data = JSON.parse(btn.getAttribute('data-contact'));
                    } catch (e) { return; }

                    setText('cm-name', data.name);
                    const emailLink = document.getElementById('cm-email-link');
                    if (emailLink && data.email) {
                        emailLink.href = 'mailto:' + data.email;
                        emailLink.textContent = data.email;
                    } else if (emailLink) {
                        emailLink.removeAttribute('href');
                        emailLink.textContent = '—';
                    }
                    setText('cm-phone', data.phone);
                    setText('cm-subject', data.subject);
                    setText('cm-message', data.message);

                    const dt = data.created_at ? new Date(data.created_at) : null;
                    setText('cm-date', dt && !isNaN(dt.getTime()) ? dt.toLocaleString() : '—');

                    if (markReadForm && base && data.id && !data.is_read) {
                        markReadForm.action = base + '/' + data.id + '/read';
                        markReadForm.classList.remove('hidden');
                    } else if (markReadForm) {
                        markReadForm.classList.add('hidden');
                    }

                    if (window.HSOverlay) {
                        HSOverlay.open(modalEl);
                    }
                });
            });

        })();
    </script>
@endpush