<?php

namespace App\Services;

use App\Models\Setting;

class IntegrationSettingsService
{
    /**
     * @return array<string, mixed>
     */
    public function all(): array
    {
        return [
            'platform' => [
                'payment_enabled' => (string) setting('payment_enabled', '1') === '1',
            ],
            'google_ads' => [
                'id' => Setting::getString('google_ads_id'),
                'conversion_label' => Setting::getString('google_ads_conversion_label'),
            ],
            'razorpay' => [
                'key' => Setting::getString('razorpay_key'),
                'secret' => Setting::getString('razorpay_secret'),
            ],
            'whatsapp' => [
                'enabled' => Setting::getBool('integrations.whatsapp.enabled', false),
                // Keep legacy keys, but also support the simpler keys requested by user.
                'api_token' => Setting::getString('whatsapp_token') ?? Setting::getString('integrations.whatsapp.api_token'),
                'phone_number_id' => Setting::getString('phone_number_id') ?? Setting::getString('integrations.whatsapp.phone_number_id'),
                'webhook_verify_token' => Setting::getString('webhook_verify_token') ?? Setting::getString('integrations.whatsapp.webhook_verify_token'),
                'inbound_organization_id' => Setting::getString('integrations.whatsapp.inbound_organization_id'),
            ],
            'payment' => [
                'enabled' => Setting::getBool('integrations.payment.enabled', false),
                'key' => Setting::getString('integrations.payment.key'),
                'secret' => Setting::getString('integrations.payment.secret'),
            ],
            'smtp' => [
                'enabled' => Setting::getBool('integrations.smtp.enabled', false),
                'host' => Setting::getString('integrations.smtp.host'),
                'port' => Setting::getString('integrations.smtp.port'),
                'username' => Setting::getString('integrations.smtp.username'),
                'password' => Setting::getString('integrations.smtp.password'),
            ],
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function save(array $data): void
    {
        // Platform billing toggle (paid SaaS vs free mode).
        // Checkbox fields may be absent when unchecked; only update when key is present.
        if (array_key_exists('platform_payment_enabled', $data)) {
            Setting::putString('payment_enabled', ! empty($data['platform_payment_enabled']) ? '1' : '0');
        }

        Setting::putString('google_ads_id', $data['google_ads_id'] ?? null);
        Setting::putString('google_ads_conversion_label', $data['google_ads_conversion_label'] ?? null);

        Setting::putString('razorpay_key', $data['razorpay_key'] ?? null);
        Setting::putString('razorpay_secret', $data['razorpay_secret'] ?? null);

        Setting::putString('integrations.whatsapp.enabled', ! empty($data['whatsapp_enabled']) ? '1' : '0');
        // Store to both legacy and simplified keys.
        Setting::putString('integrations.whatsapp.api_token', $data['whatsapp_api_token'] ?? null);
        Setting::putString('integrations.whatsapp.phone_number_id', $data['whatsapp_phone_number_id'] ?? null);
        Setting::putString('integrations.whatsapp.webhook_verify_token', $data['whatsapp_webhook_verify_token'] ?? null);

        Setting::putString('whatsapp_token', $data['whatsapp_api_token'] ?? null);
        Setting::putString('phone_number_id', $data['whatsapp_phone_number_id'] ?? null);
        Setting::putString('webhook_verify_token', $data['whatsapp_webhook_verify_token'] ?? null);

        Setting::putString('integrations.whatsapp.inbound_organization_id', $data['whatsapp_inbound_organization_id'] ?? null);

        // Payment gateway toggle (separate from platform billing mode).
        if (array_key_exists('gateway_payment_enabled', $data)) {
            Setting::putString('integrations.payment.enabled', ! empty($data['gateway_payment_enabled']) ? '1' : '0');
        }
        Setting::putString('integrations.payment.key', $data['payment_key'] ?? null);
        Setting::putString('integrations.payment.secret', $data['payment_secret'] ?? null);

        Setting::putString('integrations.smtp.enabled', ! empty($data['smtp_enabled']) ? '1' : '0');
        Setting::putString('integrations.smtp.host', $data['smtp_host'] ?? null);
        Setting::putString('integrations.smtp.port', $data['smtp_port'] ?? null);
        Setting::putString('integrations.smtp.username', $data['smtp_username'] ?? null);
        Setting::putString('integrations.smtp.password', $data['smtp_password'] ?? null);
    }
}

