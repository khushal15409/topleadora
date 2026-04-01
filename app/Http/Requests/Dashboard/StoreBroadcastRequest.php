<?php

namespace App\Http\Requests\Dashboard;

use App\Models\Broadcast;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreBroadcastRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', Broadcast::class) ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'message' => ['required', 'string', 'max:5000'],
            'send_to_all' => ['sometimes', 'boolean'],
            'lead_ids' => ['sometimes', 'array'],
            'lead_ids.*' => ['integer'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            if ($this->boolean('send_to_all')) {
                return;
            }
            $ids = $this->input('lead_ids', []);
            if (! is_array($ids) || count($ids) === 0) {
                $validator->errors()->add('lead_ids', __('Select at least one lead or enable “Send to all”.'));

                return;
            }
        });
    }
}
