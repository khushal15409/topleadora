<?php

namespace App\Http\Requests\Dashboard;

use App\Models\Lead;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LeadQuickActionRequest extends FormRequest
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
        $stages = Lead::pipelineStages();

        return [
            'action' => ['required', 'string', Rule::in(['status', 'followup', 'note'])],
            'status' => ['required_if:action,status', 'string', Rule::in($stages)],
            'next_followup_at' => ['required_if:action,followup', 'nullable', 'date'],
            'note' => ['required_if:action,note', 'string', 'max:5000'],
        ];
    }
}
