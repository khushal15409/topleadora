<?php

namespace App\Http\Controllers\Admin\Marketing;

use App\Http\Controllers\Controller;
use App\Models\MarketingFormField;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class FormFieldController extends Controller
{
    public function index(): View
    {
        $fields = MarketingFormField::query()->orderBy('sort_order')->orderBy('id')->get();

        return view('admin.marketing.form-fields.index', compact('fields'));
    }

    public function create(): View
    {
        return view('admin.marketing.form-fields.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'field_key' => ['nullable', 'string', 'max:64', 'unique:marketing_form_fields,field_key'],
            'label' => ['required', 'string', 'max:255'],
            'field_type' => ['required', Rule::in(['text', 'textarea', 'email'])],
            'is_required' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:65535'],
        ]);

        $key = $data['field_key'] ?? null;
        if ($key === null || $key === '') {
            $key = Str::slug($data['label']);
        } else {
            $key = Str::slug($key);
        }

        MarketingFormField::query()->create([
            'field_key' => $key,
            'label' => $data['label'],
            'field_type' => $data['field_type'],
            'is_required' => $request->boolean('is_required'),
            'is_active' => $request->boolean('is_active', true),
            'sort_order' => $data['sort_order'] ?? 0,
        ]);

        return redirect()->route('admin.marketing.form-fields.index')->with('success', __('Field created.'));
    }

    public function edit(MarketingFormField $form_field): View
    {
        return view('admin.marketing.form-fields.edit', ['field' => $form_field]);
    }

    public function update(Request $request, MarketingFormField $form_field): RedirectResponse
    {
        $data = $request->validate([
            'field_key' => ['required', 'string', 'max:64', Rule::unique('marketing_form_fields', 'field_key')->ignore($form_field->id)],
            'label' => ['required', 'string', 'max:255'],
            'field_type' => ['required', Rule::in(['text', 'textarea', 'email'])],
            'is_required' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:65535'],
        ]);

        $form_field->update([
            'field_key' => Str::slug($data['field_key']),
            'label' => $data['label'],
            'field_type' => $data['field_type'],
            'is_required' => $request->boolean('is_required'),
            'is_active' => $request->boolean('is_active', true),
            'sort_order' => $data['sort_order'] ?? 0,
        ]);

        return redirect()->route('admin.marketing.form-fields.index')->with('success', __('Field updated.'));
    }

    public function destroy(MarketingFormField $form_field): RedirectResponse
    {
        $form_field->delete();

        return redirect()->route('admin.marketing.form-fields.index')->with('success', __('Field deleted.'));
    }
}
