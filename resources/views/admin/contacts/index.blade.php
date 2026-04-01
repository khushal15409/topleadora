@extends('layouts.admin')

@section('title', 'Contact Messages')

@push('vendor-css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.11/css/dataTables.bootstrap5.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" crossorigin="anonymous">
@endpush

@section('content')
    <div class="d-none" data-admin-contacts-base="{{ route('admin.contacts.index') }}" aria-hidden="true"></div>

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
        <div>
            <h4 class="mb-1">Contact Messages</h4>
            <p class="mb-0 text-body-secondary">
                Inquiries submitted from the website contact form and landing page.
            </p>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($contacts->isEmpty())
        <div class="card">
            <div class="card-body text-body-secondary">
                No contact messages yet.
            </div>
        </div>
    @else
        <div class="card admin-contacts-card">
            <div class="card-datatable table-responsive">
                <table id="dt-contacts" class="table table-hover table-sm border-top">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Subject</th>
                            <th>Message</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
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
                            <tr class="{{ $c->is_read ? '' : 'admin-contact-row-unread' }}">
                                <td data-order="{{ $c->id }}">{{ $c->id }}</td>
                                <td class="fw-medium">{{ $c->name }}</td>
                                <td>{{ $c->email }}</td>
                                <td>{{ $c->phone ?? '—' }}</td>
                                <td>{{ $c->subject ?? '—' }}</td>
                                <td>
                                    <span class="text-body-secondary small">{{ \Illuminate\Support\Str::limit($c->message, 72) }}</span>
                                    @if (\Illuminate\Support\Str::length($c->message) > 72)
                                        <span class="text-body-tertiary">…</span>
                                    @endif
                                </td>
                                <td data-order="{{ $c->created_at?->timestamp ?? 0 }}">
                                    <span class="text-body-secondary small">{{ $c->created_at?->format('M j, Y H:i') }}</span>
                                </td>
                                <td>
                                    @if ($c->is_read)
                                        <span class="badge rounded-pill bg-label-secondary">Read</span>
                                    @else
                                        <span class="badge rounded-pill bg-label-warning">New</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="d-inline-flex flex-wrap gap-1 justify-content-end">
                                        <button
                                            type="button"
                                            class="btn btn-sm btn-icon btn-text-primary rounded-pill js-contact-view"
                                            title="View"
                                            data-contact="{{ e($json) }}"
                                        >
                                            <i class="icon-base ri ri-eye-line icon-20px"></i>
                                        </button>
                                        @if (! $c->is_read)
                                            <form
                                                action="{{ route('admin.contacts.mark-read', $c) }}"
                                                method="post"
                                                class="d-inline"
                                            >
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-icon btn-text-secondary rounded-pill" title="Mark as read">
                                                    <i class="icon-base ri ri-mail-open-line icon-20px"></i>
                                                </button>
                                            </form>
                                        @endif
                                        <form
                                            action="{{ route('admin.contacts.destroy', $c) }}"
                                            method="post"
                                            class="d-inline"
                                            onsubmit="return confirm('Delete this message permanently?');"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-icon btn-text-danger rounded-pill" title="Delete">
                                                <i class="icon-base ri ri-delete-bin-6-line icon-20px"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <div class="modal fade" id="contactMessageModal" tabindex="-1" aria-labelledby="contactMessageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="contactMessageModalLabel">Message details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-3 text-body-secondary">Name</dt>
                        <dd class="col-sm-9" id="cm-name"></dd>
                        <dt class="col-sm-3 text-body-secondary">Email</dt>
                        <dd class="col-sm-9"><a href="#" id="cm-email-link"></a></dd>
                        <dt class="col-sm-3 text-body-secondary">Phone</dt>
                        <dd class="col-sm-9" id="cm-phone"></dd>
                        <dt class="col-sm-3 text-body-secondary">Subject</dt>
                        <dd class="col-sm-9" id="cm-subject"></dd>
                        <dt class="col-sm-3 text-body-secondary">Date</dt>
                        <dd class="col-sm-9" id="cm-date"></dd>
                        <dt class="col-sm-3 text-body-secondary align-self-start pt-1">Message</dt>
                        <dd class="col-sm-9">
                            <pre class="mb-0 text-body" id="cm-message" style="white-space: pre-wrap; font-family: inherit; font-size: 0.9375rem;"></pre>
                        </dd>
                    </dl>
                </div>
                <div class="modal-footer flex-wrap gap-2">
                    <form id="contactMarkReadForm" method="post" class="d-none me-auto">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-primary" id="contactMarkReadSubmit">Mark as read</button>
                    </form>
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('vendor-js')
    <script src="https://cdn.datatables.net/1.13.11/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.13.11/js/dataTables.bootstrap5.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js" crossorigin="anonymous"></script>
@endpush

@push('page-js')
    <script>
        (function () {
            const modalEl = document.getElementById('contactMessageModal');
            const baseEl = document.querySelector('[data-admin-contacts-base]');
            if (!modalEl || typeof bootstrap === 'undefined' || !baseEl) {
                return;
            }
            const modal = new bootstrap.Modal(modalEl);
            const markReadForm = document.getElementById('contactMarkReadForm');
            const base = (baseEl.getAttribute('data-admin-contacts-base') || '').replace(/\/$/, '');

            function setText(id, text) {
                const el = document.getElementById(id);
                if (el) {
                    el.textContent = text || '—';
                }
            }

            document.querySelectorAll('.js-contact-view').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    let data;
                    try {
                        data = JSON.parse(btn.getAttribute('data-contact'));
                    } catch (e) {
                        return;
                    }
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
                    const msgEl = document.getElementById('cm-message');
                    if (msgEl) {
                        msgEl.textContent = data.message || '';
                    }
                    const dt = data.created_at ? new Date(data.created_at) : null;
                    setText('cm-date', dt && !isNaN(dt.getTime()) ? dt.toLocaleString() : '—');

                    if (markReadForm && base && data.id && !data.is_read) {
                        markReadForm.action = base + '/' + data.id + '/read';
                        markReadForm.classList.remove('d-none');
                    } else if (markReadForm) {
                        markReadForm.classList.add('d-none');
                    }

                    modal.show();
                });
            });
        })();
    </script>
    <script>
        (function () {
            if (typeof jQuery === 'undefined' || !jQuery.fn.DataTable) {
                return;
            }
            const $table = jQuery('#dt-contacts');
            if (!$table.length || $table.find('tbody tr').length === 0) {
                return;
            }
            $table.DataTable({
                responsive: true,
                pageLength: 10,
                order: [[0, 'desc']],
                columnDefs: [
                    { orderable: false, searchable: false, targets: -1 },
                    { className: 'align-middle', targets: '_all' },
                ],
                language: {
                    search: '',
                    searchPlaceholder: 'Search…',
                    lengthMenu: 'Show _MENU_ entries',
                    info: 'Showing _START_ to _END_ of _TOTAL_',
                    infoEmpty: 'No messages',
                    infoFiltered: '(filtered from _MAX_)',
                    zeroRecords: 'No matching messages',
                    paginate: { next: 'Next', previous: 'Prev' },
                },
                dom:
                    "<'row align-items-center justify-content-between g-2 mb-3 px-3 pt-3'<'col-sm-12 col-md-6 d-flex align-items-center'l><'col-sm-12 col-md-6 d-flex justify-content-md-end'f>>" +
                    "<'table-responsive'tr>" +
                    "<'row align-items-center justify-content-between g-2 px-3 pb-3'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 d-flex justify-content-md-end'p>>",
            });
        })();
    </script>
@endpush
