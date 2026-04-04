<?php

namespace App\Http\Requests;

use App\Models\MarketingFormField;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class StoreMarketingLeadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'utm_campaign' => $this->input('utm_campaign')
                ?: $this->input('campaign')
                ?: $this->query('utm_campaign')
                ?: $this->query('campaign'),
            'utm_source' => $this->input('utm_source') ?: $this->query('utm_source'),
            'utm_medium' => $this->input('utm_medium') ?: $this->query('utm_medium'),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'digits:10'],
            'service_id' => ['required', 'integer', Rule::exists('services', 'id')->where('is_active', true)],
            'country_id' => ['required', 'integer', Rule::exists('countries', 'id')->where('is_active', true)],
            'city' => ['required', 'string', 'max:128'],
            'source_page' => ['nullable', 'string', 'max:255'],
            'utm_source' => ['nullable', 'string', 'max:255'],
            'utm_medium' => ['nullable', 'string', 'max:255'],
            'utm_campaign' => ['nullable', 'string', 'max:255'],
            'extra' => ['nullable', 'array'],
        ];

        if (! Schema::hasTable('marketing_form_fields')) {
            return $rules;
        }

        foreach (MarketingFormField::query()->activeOrdered()->get() as $field) {
            $key = 'extra.'.$field->field_key;
            if ($field->field_type === 'email') {
                $base = $field->is_required
                    ? ['required', 'email', 'max:255']
                    : ['nullable', 'email', 'max:255'];
                $rules[$key] = $base;
            } elseif ($field->field_type === 'textarea') {
                $rules[$key] = $field->is_required
                    ? ['required', 'string', 'max:5000']
                    : ['nullable', 'string', 'max:5000'];
            } else {
                $rules[$key] = $field->is_required
                    ? ['required', 'string', 'max:2000']
                    : ['nullable', 'string', 'max:2000'];
            }
        }

        return $rules;
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'service_id.exists' => __('Please choose a valid service.'),
            'country_id.exists' => __('Please choose a valid country.'),
            'phone.digits' => __('Enter a valid 10-digit mobile number.'),
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(response()->json([
            'status' => false,
            'ok' => false,
            'message' => __('Please check the form and try again.'),
            'errors' => $validator->errors(),
        ], 422));
    }
}
