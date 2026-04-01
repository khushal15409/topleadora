<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateIntegrationsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('settings.manage') ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'platform_payment_enabled' => ['sometimes', 'boolean'],
            'google_ads_id' => ['nullable', 'string', 'max:64'],
            'google_ads_conversion_label' => ['nullable', 'string', 'max:128'],

            'razorpay_key' => ['nullable', 'string', 'max:64'],
            'razorpay_secret' => ['nullable', 'string', 'max:5000'],

            'whatsapp_enabled' => ['sometimes', 'boolean'],
            'whatsapp_api_token' => ['nullable', 'string', 'max:5000'],
            'whatsapp_phone_number_id' => ['nullable', 'string', 'max:255'],
            'whatsapp_webhook_verify_token' => ['nullable', 'string', 'max:255'],
            'whatsapp_inbound_organization_id' => ['nullable', 'integer', 'exists:organizations,id'],

            'gateway_payment_enabled' => ['sometimes', 'boolean'],
            'payment_key' => ['nullable', 'string', 'max:255'],
            'payment_secret' => ['nullable', 'string', 'max:5000'],

            'smtp_enabled' => ['sometimes', 'boolean'],
            'smtp_host' => ['nullable', 'string', 'max:255'],
            'smtp_port' => ['nullable', 'integer', 'min:1', 'max:65535'],
            'smtp_username' => ['nullable', 'string', 'max:255'],
            'smtp_password' => ['nullable', 'string', 'max:5000'],
        ];
    }
}

