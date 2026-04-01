<?php

namespace App\Http\Requests\Admin;

use App\Support\Permissions;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrganizationStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can(Permissions::ORGANIZATIONS_MANAGE) ?? false;
    }

    protected function prepareForValidation(): void
    {
        if ($this->input('slug') === '') {
            $this->merge(['slug' => null]);
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:organizations,slug'],
            'status' => ['required', 'string', Rule::in(['active', 'inactive'])],
            'plan_id' => ['nullable', 'integer', 'exists:plans,id'],
            'trial_ends_at' => ['nullable', 'date'],
            'mobile_number' => ['nullable', 'string', 'max:32'],
        ];
    }
}
