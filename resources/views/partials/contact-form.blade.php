@php
    $contactFormReturn = $contactFormReturn ?? 'landing';
    $returnValue = $contactFormReturn === 'contact' ? 'contact' : 'landing';
@endphp

@if (session('contact_success'))
    <div
        id="contact-success-toast-payload"
        class="d-none"
        data-message="{{ e(__('Your message has been sent successfully')) }}"
        aria-hidden="true"
    ></div>
@endif

@if ($errors->any())
    <div class="alert alert-danger mb-4" role="alert">
        <ul class="mb-0 ps-3">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form
    method="post"
    action="{{ route('contact.store') }}"
    class="contact-form p-4 p-lg-5"
    id="site-contact-form"
    novalidate
>
    @csrf
    <input type="hidden" name="_return" value="{{ $returnValue }}">

    <div class="row gy-3">
        <div class="col-md-6">
            <label class="form-label fw-medium" for="contact-name">{{ __('Name') }} <span class="text-danger">*</span></label>
            <input
                type="text"
                name="name"
                id="contact-name"
                class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name') }}"
                required
                autocomplete="name"
                placeholder="{{ __('Your name') }}"
            >
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6">
            <label class="form-label fw-medium" for="contact-email">{{ __('Email') }} <span class="text-danger">*</span></label>
            <input
                type="email"
                name="email"
                id="contact-email"
                class="form-control @error('email') is-invalid @enderror"
                value="{{ old('email') }}"
                required
                autocomplete="email"
                placeholder="name@company.com"
            >
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6">
            <label class="form-label fw-medium" for="contact-phone">{{ __('Phone') }}</label>
            <input
                type="text"
                name="phone"
                id="contact-phone"
                class="form-control @error('phone') is-invalid @enderror"
                value="{{ old('phone') }}"
                autocomplete="tel"
                placeholder="{{ __('Optional') }}"
            >
            @error('phone')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6">
            <label class="form-label fw-medium" for="contact-subject">{{ __('Subject') }}</label>
            <input
                type="text"
                name="subject"
                id="contact-subject"
                class="form-control @error('subject') is-invalid @enderror"
                value="{{ old('subject') }}"
                placeholder="{{ __('How can we help?') }}"
            >
            @error('subject')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-12">
            <label class="form-label fw-medium" for="contact-message">{{ __('Message') }} <span class="text-danger">*</span></label>
            <textarea
                name="message"
                id="contact-message"
                class="form-control @error('message') is-invalid @enderror"
                rows="5"
                required
                placeholder="{{ __('Tell us more…') }}"
            >{{ old('message') }}</textarea>
            @error('message')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-12 pt-2">
            <button type="submit" class="btn btn-submit" id="site-contact-form-submit">{{ __('Send Message') }}</button>
        </div>
    </div>
</form>

{{-- Toast container + jQuery Validation (landing layout @stack('scripts')) --}}
@once
    @push('scripts')
        <div
            class="toast-container position-fixed bottom-0 end-0 p-3 contact-toast-container"
            style="z-index: 1090"
            aria-live="polite"
            aria-atomic="true"
        >
            <div
                id="contactToastSuccess"
                class="toast align-items-center text-bg-success border-0 shadow"
                role="alert"
                data-bs-delay="6500"
            >
                <div class="d-flex">
                    <div class="toast-body fw-medium" id="contactToastSuccessBody"></div>
                    <button
                        type="button"
                        class="btn-close btn-close-white me-2 m-auto"
                        data-bs-dismiss="toast"
                        aria-label="{{ __('Close') }}"
                    ></button>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.20.0/dist/jquery.validate.min.js" crossorigin="anonymous"></script>
        <script>
            (function ($) {
                'use strict';

                function showContactSuccessToast(message) {
                    if (typeof bootstrap === 'undefined' || !bootstrap.Toast) {
                        return;
                    }
                    var el = document.getElementById('contactToastSuccess');
                    var body = document.getElementById('contactToastSuccessBody');
                    if (!el || !body) {
                        return;
                    }
                    body.textContent = message || '';
                    var toast = bootstrap.Toast.getOrCreateInstance(el, { animation: true, autohide: true });
                    toast.show();
                }

                function initContactFormValidation() {
                    var $form = $('#site-contact-form');
                    if (!$form.length || typeof $.fn.validate !== 'function') {
                        return;
                    }

                    $.validator.addMethod(
                        'phoneOptional',
                        function (value) {
                            if (!value || !value.trim()) {
                                return true;
                            }
                            return /^[\d\s+().\-]{6,50}$/.test(value.trim());
                        },
                        @json(__('Enter a valid phone number (digits and + ( ) - allowed).'))
                    );

                    $form.validate({
                        rules: {
                            name: { required: true, maxlength: 255 },
                            email: { required: true, email: true, maxlength: 255 },
                            phone: { maxlength: 50, phoneOptional: true },
                            subject: { maxlength: 255 },
                            message: { required: true, maxlength: 10000, minlength: 3 },
                        },
                        messages: {
                            name: {
                                required: @json(__('Please enter your name.')),
                                maxlength: @json(__('Name must be at most 255 characters.')),
                            },
                            email: {
                                required: @json(__('Please enter your email address.')),
                                email: @json(__('Please enter a valid email address.')),
                                maxlength: @json(__('Email must be at most 255 characters.')),
                            },
                            phone: { maxlength: @json(__('Phone must be at most 50 characters.')) },
                            subject: { maxlength: @json(__('Subject must be at most 255 characters.')) },
                            message: {
                                required: @json(__('Please enter your message.')),
                                minlength: @json(__('Message is too short.')),
                                maxlength: @json(__('Message must be at most 10,000 characters.')),
                            },
                        },
                        errorElement: 'div',
                        errorClass: 'invalid-feedback d-block mt-1',
                        onfocusout: function (element) {
                            this.element(element);
                        },
                        highlight: function (element) {
                            $(element).addClass('is-invalid');
                        },
                        unhighlight: function (element) {
                            $(element).removeClass('is-invalid');
                        },
                        errorPlacement: function (error, element) {
                            var $group = element.closest('.col-md-6, .col-12');
                            if ($group.length) {
                                $group.append(error);
                            } else {
                                error.insertAfter(element);
                            }
                        },
                    });
                }

                $(function () {
                    initContactFormValidation();
                });

                window.addEventListener('load', function () {
                    var $payload = jQuery('#contact-success-toast-payload');
                    if (!$payload.length) {
                        return;
                    }
                    showContactSuccessToast($payload.data('message'));
                    $payload.remove();
                });
            })(jQuery);
        </script>
    @endpush
@endonce
