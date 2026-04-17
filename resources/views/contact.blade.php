@extends('layouts.landing')

@section('meta_title', 'Contact us | '.config('app.name', 'TopLeadOra'))
@section('meta_description', 'Get in touch with the TopLeadOra team. We will respond as soon as possible.')
@section('canonical_url', route('contact', absolute: true))

@section('content')
    <section class="contact section light-background py-5 pt-5 mt-5">
        <div class="container section-title" data-aos="fade-up">
            <span class="description-title">{{ __('Contact') }}</span>
            <h1 class="mb-2">{{ __('Contact us — Get in Touch') }}</h1>
            <p>{{ __('Send us a message and we will get back to you shortly.') }}</p>
        </div>
        <div class="container" data-aos="fade-up" data-aos-delay="80">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    @include('partials.contact-form', ['contactFormReturn' => 'contact'])
                </div>
            </div>
        </div>
    </section>
@endsection
