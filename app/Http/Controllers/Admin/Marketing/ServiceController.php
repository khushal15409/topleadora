<?php

namespace App\Http\Controllers\Admin\Marketing;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(): View
    {
        $services = Service::query()->orderBy('sort_order')->orderBy('name')->get();

        return view('admin.marketing.services.index', compact('services'));
    }

    public function create(): View
    {
        return view('admin.marketing.services.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:128', 'unique:services,slug'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:65535'],
            'default_content_json' => ['nullable', 'string'],
        ]);

        $slug = $data['slug'] !== null && $data['slug'] !== ''
            ? Str::slug($data['slug'])
            : Str::slug($data['name']);

        $json = null;
        if (! empty($data['default_content_json'])) {
            $decoded = json_decode($data['default_content_json'], true);
            $json = is_array($decoded) ? $decoded : null;
        }

        Service::query()->create([
            'name' => $data['name'],
            'slug' => $slug,
            'is_active' => $request->boolean('is_active', true),
            'sort_order' => $data['sort_order'] ?? 0,
            'default_content_json' => $json,
        ]);

        return redirect()->route('admin.marketing.services.index')->with('success', __('Service created.'));
    }

    public function edit(Service $service): View
    {
        return view('admin.marketing.services.edit', compact('service'));
    }

    public function update(Request $request, Service $service): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:128', 'unique:services,slug,'.$service->id],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:65535'],
            'default_content_json' => ['nullable', 'string'],
        ]);

        $json = $service->default_content_json;
        if (array_key_exists('default_content_json', $data)) {
            if ($data['default_content_json'] === null || trim((string) $data['default_content_json']) === '') {
                $json = null;
            } else {
                $decoded = json_decode($data['default_content_json'], true);
                $json = is_array($decoded) ? $decoded : $service->default_content_json;
            }
        }

        $service->update([
            'name' => $data['name'],
            'slug' => Str::slug($data['slug']),
            'is_active' => $request->boolean('is_active', true),
            'sort_order' => $data['sort_order'] ?? 0,
            'default_content_json' => $json,
        ]);

        return redirect()->route('admin.marketing.services.index')->with('success', __('Service updated.'));
    }

    public function destroy(Service $service): RedirectResponse
    {
        if ($service->landingPages()->exists()) {
            return redirect()->route('admin.marketing.services.index')->withErrors([
                'delete' => __('Remove or reassign landing pages before deleting this service.'),
            ]);
        }

        $service->delete();

        return redirect()->route('admin.marketing.services.index')->with('success', __('Service deleted.'));
    }
}
