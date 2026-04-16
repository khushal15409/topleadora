@once
    {{-- Global toaster + confirmation modal (no layout changes). --}}
    <div
        id="app-toaster"
        class="toast-container position-fixed top-0 end-0 p-3"
        style="z-index: 2000"
        aria-live="polite"
        aria-atomic="true"
    ></div>

    {{-- Central confirmation modal to replace window.confirm() --}}
    <div class="modal fade" id="appConfirmModal" tabindex="-1" aria-labelledby="appConfirmModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="appConfirmModalLabel">{{ __('Please confirm') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
                </div>
                <div class="modal-body" id="appConfirmModalBody">{{ __('Are you sure?') }}</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" id="appConfirmCancelBtn">{{ __('Cancel') }}</button>
                    <button type="button" class="btn btn-primary" id="appConfirmOkBtn">{{ __('Confirm') }}</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function () {
            'use strict';

            function ensureContainer() {
                return document.getElementById('app-toaster');
            }

            function createToastEl(message, type) {
                var container = ensureContainer();
                if (!container) return null;

                var bg = 'text-bg-primary';
                if (type === 'success') bg = 'text-bg-success';
                if (type === 'error') bg = 'text-bg-danger';
                if (type === 'warning') bg = 'text-bg-warning';
                if (type === 'info') bg = 'text-bg-info';

                var el = document.createElement('div');
                el.className = 'toast align-items-center ' + bg + ' border-0 shadow mb-2';
                el.setAttribute('role', 'alert');
                el.setAttribute('aria-live', 'assertive');
                el.setAttribute('aria-atomic', 'true');
                el.dataset.bsDelay = '5500';
                el.innerHTML =
                    '<div class="d-flex">' +
                        '<div class="toast-body fw-medium"></div>' +
                        '<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="{{ __('Close') }}"></button>' +
                    '</div>';

                var body = el.querySelector('.toast-body');
                if (body) body.textContent = message || '';
                container.appendChild(el);
                return el;
            }

            function showToast(type, message) {
                var el = createToastEl(message, type);
                if (!el) return;

                // Prefer Bootstrap Toast if available (most layouts include bootstrap.bundle).
                if (typeof bootstrap !== 'undefined' && bootstrap.Toast) {
                    var t = bootstrap.Toast.getOrCreateInstance(el, { animation: true, autohide: true });
                    el.addEventListener('hidden.bs.toast', function () {
                        try { el.remove(); } catch (e) {}
                    }, { once: true });
                    t.show();
                    return;
                }

                // Minimal fallback (no bootstrap): auto-remove.
                el.style.display = 'block';
                setTimeout(function () {
                    try { el.remove(); } catch (e) {}
                }, 5500);
            }

            window.toaster = window.toaster || {
                success: function (m) { showToast('success', m); },
                error: function (m) { showToast('error', m); },
                warning: function (m) { showToast('warning', m); },
                info: function (m) { showToast('info', m); },
            };

            // Replace confirm() with a single reusable modal.
            var confirmModalEl = document.getElementById('appConfirmModal');
            var confirmBodyEl = document.getElementById('appConfirmModalBody');
            var okBtn = document.getElementById('appConfirmOkBtn');
            var cancelBtn = document.getElementById('appConfirmCancelBtn');
            var modalInstance = null;
            var pendingResolve = null;

            function openConfirm(message) {
                return new Promise(function (resolve) {
                    pendingResolve = resolve;
                    if (confirmBodyEl) confirmBodyEl.textContent = message || '{{ __('Are you sure?') }}';

                    if (typeof bootstrap !== 'undefined' && bootstrap.Modal && confirmModalEl) {
                        modalInstance = bootstrap.Modal.getOrCreateInstance(confirmModalEl, { backdrop: 'static', keyboard: true });
                        modalInstance.show();
                    } else {
                        // Fallback: use toaster + auto-cancel
                        window.toaster.warning(message || 'Are you sure?');
                        resolve(false);
                    }
                });
            }

            if (okBtn) {
                okBtn.addEventListener('click', function () {
                    if (modalInstance) modalInstance.hide();
                    if (pendingResolve) pendingResolve(true);
                    pendingResolve = null;
                });
            }
            if (cancelBtn) {
                cancelBtn.addEventListener('click', function () {
                    if (modalInstance) modalInstance.hide();
                    if (pendingResolve) pendingResolve(false);
                    pendingResolve = null;
                });
            }
            if (confirmModalEl) {
                confirmModalEl.addEventListener('hidden.bs.modal', function () {
                    if (pendingResolve) pendingResolve(false);
                    pendingResolve = null;
                });
            }

            window.toasterConfirm = window.toasterConfirm || openConfirm;

            // Disallow browser alert() dialogs globally (UX consistency).
            // (We do NOT override prompt/confirm globally because some editors/components may rely on synchronous return values.)
            if (typeof window.alert === 'function') {
                window.alert = function (msg) {
                    window.toaster.info(typeof msg === 'string' ? msg : String(msg || ''));
                };
            }

            // Auto-bind forms/buttons with data-confirm.
            document.addEventListener('submit', function (e) {
                var form = e.target;
                if (!form || !form.getAttribute) return;
                var msg = form.getAttribute('data-confirm');
                if (!msg) return;

                e.preventDefault();
                openConfirm(msg).then(function (ok) {
                    if (!ok) return;
                    // Prevent double submit loops
                    form.removeAttribute('data-confirm');
                    form.submit();
                });
            }, true);
        })();
    </script>
@endonce

