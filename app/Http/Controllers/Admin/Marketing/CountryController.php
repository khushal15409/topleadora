<?php

namespace App\Http\Controllers\Admin\Marketing;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CountryController extends Controller
{
    public function index(Request $request): View
    {
        $q = $request->query('q');
        $countries = Country::query()
            ->when($q, fn ($b) => $b->where('name', 'like', '%'.$q.'%')->orWhere('code', 'like', '%'.$q.'%'))
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(50)
            ->withQueryString();

        return view('admin.marketing.countries.index', compact('countries', 'q'));
    }

    public function create(): View
    {
        return view('admin.marketing.countries.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'size:2', 'unique:countries,code'],
            'name' => ['required', 'string', 'max:255'],
            'url_slug' => ['nullable', 'string', 'max:128', 'unique:countries,url_slug'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:65535'],
        ]);

        $slug = $data['url_slug'] !== null && $data['url_slug'] !== ''
            ? Str::slug($data['url_slug'])
            : Str::slug($data['name']);

        Country::query()->create([
            'code' => strtoupper($data['code']),
            'name' => $data['name'],
            'url_slug' => $slug,
            'is_active' => $request->boolean('is_active', true),
            'sort_order' => $data['sort_order'] ?? 0,
        ]);

        return redirect()->route('admin.marketing.countries.index')->with('success', __('Country created.'));
    }

    public function edit(Country $country): View
    {
        return view('admin.marketing.countries.edit', compact('country'));
    }

    public function update(Request $request, Country $country): RedirectResponse
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'size:2', 'unique:countries,code,'.$country->id],
            'name' => ['required', 'string', 'max:255'],
            'url_slug' => ['required', 'string', 'max:128', 'unique:countries,url_slug,'.$country->id],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:65535'],
        ]);

        $country->update([
            'code' => strtoupper($data['code']),
            'name' => $data['name'],
            'url_slug' => Str::slug($data['url_slug']),
            'is_active' => $request->boolean('is_active', true),
            'sort_order' => $data['sort_order'] ?? 0,
        ]);

        return redirect()->route('admin.marketing.countries.index')->with('success', __('Country updated.'));
    }

    public function destroy(Country $country): RedirectResponse
    {
        if ($country->landingPages()->exists()) {
            return redirect()->route('admin.marketing.countries.index')->withErrors([
                'delete' => __('Remove landing pages using this country first.'),
            ]);
        }

        $country->delete();

        return redirect()->route('admin.marketing.countries.index')->with('success', __('Country deleted.'));
    }
}
