<?php

namespace App\Http\Controllers\Admin\Marketing;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\LandingPage;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class LandingPageController extends Controller
{
    public function index(): View
    {
        $pages = LandingPage::query()
            ->with(['service', 'country'])
            ->orderBy('sort_order')
            ->orderBy('slug')
            ->get();

        return view('admin.marketing.landing-pages.index', compact('pages'));
    }

    public function create(): View
    {
        $services = Service::query()->orderBy('sort_order')->orderBy('name')->get();
        $countries = Country::query()->orderBy('sort_order')->orderBy('name')->get();

        return view('admin.marketing.landing-pages.create', compact('services', 'countries'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        LandingPage::query()->create($data);

        return redirect()->route('admin.marketing.landing-pages.index')->with('success', __('Landing page created.'));
    }

    public function edit(LandingPage $landing_page): View
    {
        $services = Service::query()->orderBy('sort_order')->orderBy('name')->get();
        $countries = Country::query()->orderBy('sort_order')->orderBy('name')->get();

        return view('admin.marketing.landing-pages.edit', [
            'landingPage' => $landing_page,
            'services' => $services,
            'countries' => $countries,
        ]);
    }

    public function update(Request $request, LandingPage $landing_page): RedirectResponse
    {
        $data = $this->validated($request, $landing_page->id);

        $landing_page->update($data);

        return redirect()->route('admin.marketing.landing-pages.index')->with('success', __('Landing page updated.'));
    }

    public function destroy(LandingPage $landing_page): RedirectResponse
    {
        $landing_page->delete();

        return redirect()->route('admin.marketing.landing-pages.index')->with('success', __('Landing page deleted.'));
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?int $ignoreId = null): array
    {
        $slugRule = Rule::unique('landing_pages', 'slug');
        if ($ignoreId !== null) {
            $slugRule = $slugRule->ignore($ignoreId);
        }

        $pairUnique = Rule::unique('landing_pages')->where(function ($q) use ($request) {
            return $q->where('service_id', (int) $request->input('service_id'))
                ->where('country_id', (int) $request->input('country_id'));
        });
        if ($ignoreId !== null) {
            $pairUnique = $pairUnique->ignore($ignoreId);
        }

        $data = $request->validate([
            'service_id' => ['required', 'exists:services,id', $pairUnique],
            'country_id' => ['required', 'exists:countries,id'],
            'slug' => ['required', 'string', 'max:191', 'regex:/^[a-z0-9\-]+$/', $slugRule],
            'meta_title' => ['required', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
            'meta_keywords' => ['nullable', 'string', 'max:512'],
            'robots_meta' => ['nullable', 'string', 'max:128'],
            'seo_body' => ['nullable', 'string'],
            'content_json' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:65535'],
        ]);

        $content = null;
        if (! empty($data['content_json'])) {
            $decoded = json_decode($data['content_json'], true);
            $content = is_array($decoded) ? $decoded : null;
        }

        return [
            'service_id' => (int) $data['service_id'],
            'country_id' => (int) $data['country_id'],
            'slug' => Str::lower($data['slug']),
            'meta_title' => $data['meta_title'],
            'meta_description' => $data['meta_description'] ?? null,
            'meta_keywords' => $data['meta_keywords'] ?? null,
            'robots_meta' => $data['robots_meta'] ?? 'index,follow',
            'seo_body' => $data['seo_body'] ?? null,
            'content_json' => $content,
            'is_active' => $request->boolean('is_active', true),
            'sort_order' => $data['sort_order'] ?? 0,
        ];
    }
}
