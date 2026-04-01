<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;

class StoreContactMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'subject' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:10000'],
            '_return' => ['nullable', 'string', 'in:contact,landing'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => is_string($this->input('name')) ? trim(strip_tags($this->input('name'))) : $this->input('name'),
            'email' => is_string($this->input('email')) ? trim(strtolower($this->input('email'))) : $this->input('email'),
            'phone' => is_string($this->input('phone')) ? trim(strip_tags($this->input('phone'))) : $this->input('phone'),
            'subject' => is_string($this->input('subject')) ? trim(strip_tags($this->input('subject'))) : $this->input('subject'),
            'message' => is_string($this->input('message')) ? trim($this->input('message')) : $this->input('message'),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function contactPayload(): array
    {
        return Arr::only(
            $this->validated(),
            ['name', 'email', 'phone', 'subject', 'message']
        );
    }
}
