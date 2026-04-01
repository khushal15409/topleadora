<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $user = $this->user();

        return [
            'name' => ['required', 'string', 'min:2', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user?->id),
            ],
            'phone' => ['nullable', 'string', 'max:32', 'regex:/^[\d\s\-+().]*$/'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $phone = $this->input('phone');
        $phone = is_string($phone) ? trim(strip_tags($phone)) : $phone;
        $this->merge([
            'name' => is_string($this->input('name')) ? trim(strip_tags($this->input('name'))) : $this->input('name'),
            'email' => is_string($this->input('email')) ? trim(strtolower($this->input('email'))) : $this->input('email'),
            'phone' => $phone === '' ? null : $phone,
        ]);
    }
}
