<?php

namespace App\Http\Requests\Dashboard;

use App\Models\Lead;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLeadRequest extends FormRequest
{
    public function authorize(): bool
    {
        $lead = $this->route('lead');

        return $lead instanceof Lead && $this->user()?->can('update', $lead);
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
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:32'],
            'city' => ['nullable', 'string', 'max:128'],
            'country' => ['nullable', 'string', 'max:120'],
            'niche' => ['nullable', 'string', 'max:128', Rule::exists('lead_niches', 'slug')],
            'source' => ['required', 'string', Rule::in($sources)],
            'source_page' => ['nullable', 'string', 'max:128'],
            'campaign' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'string', Rule::in($stages)],
            'notes' => ['nullable', 'string', 'max:10000'],
            'message' => ['nullable', 'string', 'max:5000'],
            'next_followup_at' => ['nullable', 'date'],
            'assigned_to' => [
                'nullable',
                'integer',
                Rule::exists('users', 'id')->where('organization_id', $orgId),
            ],
        ];
    }
}
