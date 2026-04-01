@extends('layouts.landing')

@section('meta_title', 'Contact us | '.config('app.name', 'WP-CRM'))
@section('meta_description', 'Get in touch with the WP-CRM team. We will respond as soon as possible.')

@section('content')
    <section class="contact section light-background py-5">
        <div class="container section-title" data-aos="fade-up">
            <span class="description-title">{{ __('Contact') }}</span>
            <h2>{{ __('Get in Touch') }}</h2>
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
