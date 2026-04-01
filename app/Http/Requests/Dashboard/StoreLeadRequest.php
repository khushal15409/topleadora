<?php

namespace App\Http\Requests\Dashboard;

use App\Models\Lead;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLeadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', Lead::class) ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $orgId = (int) $this->user()->organization_id;
        $stages = Lead::pipelineStages();
        $sources = array_keys(Lead::sourceOptions());

        return [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:32'],
            'source' => ['required', 'string', Rule::in($sources)],
            'status' => ['required', 'string', Rule::in($stages)],
            'notes' => ['nullable', 'string', 'max:10000'],
            'next_followup_at' => ['nullable', 'date'],
            'assigned_to' => [
                'nullable',
                'integer',
                Rule::exists('users', 'id')->where('organization_id', $orgId),
            ],
        ];
    }
}
