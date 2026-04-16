<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMarketingLeadRequest;
use App\Models\BlogPost;
use App\Models\Country;
use App\Models\LandingPage;
use App\Models\LeadLandingPage;
use App\Models\LeadNiche;
use App\Models\MarketingFormField;
use App\Models\MarketingLead;
use App\Models\Service;
use App\Support\MarketingLandingDefaults;
use App\Support\ProgrammaticLeadResolver;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;
use Throwable;

class LeadCaptureController extends Controller
{
    public function show(string $slug): View|RedirectResponse|Response
    {
        $legacyUsaRedirect = $this->redirectIfLegacyUsaLandingSlug($slug);
        if ($legacyUsaRedirect !== null) {
            return $legacyUsaRedirect;
        }

        $guessCountryId = $this->guessCountryIdFromRequest();

        if (Schema::hasTable('landing_pages') && Schema::hasTable('services')) {
            $newLanding = LandingPage::query()
                ->activeOrdered()
                ->with(['service', 'country'])
                ->where('slug', $slug)
                ->first();

            if ($newLanding !== null && $newLanding->service !== null && $newLanding->country !== null) {
                $page = $newLanding->toPublicPageArray();

                return $this->renderLeadsShow([
                    'slug' => $slug,
                    'landingModel' => null,
                    'nicheModel' => null,
                    'marketingLanding' => $newLanding,
                    'page' => $page,
                    'servicesForForm' => $this->servicesForPublicForm(),
                    'countriesForForm' => $this->countriesForPublicForm(),
                    'defaultServiceId' => $newLanding->service_id,
                    'defaultCountryId' => $newLanding->country_id,
                    'guessCountryId' => $guessCountryId,
                    'dynamicFormFields' => $this->dynamicFormFields(),
                    'relatedLandingCards' => $this->relatedMarketingLandingCards($newLanding),
                    'canonicalUrl' => route('leads.landing', $slug),
                    'schemaGraph' => $this->buildSchemaGraph($page, route('leads.landing', $slug)),
                ]);
            }

            $serviceBySlug = Service::query()->activeOrdered()->where('slug', $slug)->first();
            if ($serviceBySlug !== null) {
                $first = LandingPage::query()
                    ->activeOrdered()
                    ->where('service_id', $serviceBySlug->id)
                    ->first();
                if ($first !== null) {
                    return redirect()->route('leads.landing', $first->slug, 301);
                }
            }

            /*
             * Programmatic SEO: /leads/{slug} where slug = {service_slug}-{city_slug} (one segment).
             * Same public URL shape as stored landings; no second route registration.
             */
            $programmatic = ProgrammaticLeadResolver::resolve($slug);
            if ($programmatic !== null && Schema::hasTable('countries')) {
                $indiaId = Country::query()->where('code', 'IN')->where('is_active', true)->value('id');
                if ($indiaId !== null) {
                    $service = $programmatic['service'];
                    $page = ProgrammaticLeadResolver::buildPublicPage($slug, $service, $programmatic['city_label']);
                    $canonical = route('leads.landing', $slug);

                    return $this->renderLeadsShow([
                        'slug' => $slug,
                        'landingModel' => null,
                        'nicheModel' => null,
                        'marketingLanding' => null,
                        'page' => $page,
                        'servicesForForm' => $this->servicesForPublicForm(),
                        'countriesForForm' => $this->countriesForPublicForm(),
                        'defaultServiceId' => $service->id,
                        'defaultCountryId' => (int) $indiaId,
                        'guessCountryId' => $guessCountryId,
                        'dynamicFormFields' => $this->dynamicFormFields(),
                        'relatedLandingCards' => $this->relatedIndiaLandingPageCards($slug, $service->id),
                        'canonicalUrl' => $canonical,
                        'schemaGraph' => $this->buildSchemaGraph($page, $canonical),
                    ]);
                }
            }
        }

        $landingModel = null;
        if (Schema::hasTable('lead_landing_pages')) {
            $landingModel = LeadLandingPage::query()
                ->activeOrdered()
                ->with('niche')
                ->where('slug', $slug)
                ->first();
        }

        if ($landingModel !== null && $landingModel->niche !== null) {
            $nicheModel = $landingModel->niche;
            $page = array_merge(MarketingLandingDefaults::shell(), $landingModel->toPageArray());

            return $this->renderLeadsShow([
                'slug' => $slug,
                'landingModel' => $landingModel,
                'nicheModel' => $nicheModel,
                'marketingLanding' => null,
                'page' => $page,
                'servicesForForm' => $this->servicesForPublicForm(),
                'countriesForForm' => $this->countriesForPublicForm(),
                'defaultServiceId' => $this->serviceIdForSlug($nicheModel->slug),
                'defaultCountryId' => $this->countryIdForUrlSlug($landingModel->location_slug) ?? $guessCountryId,
                'guessCountryId' => $guessCountryId,
                'dynamicFormFields' => $this->dynamicFormFields(),
                'relatedLandingCards' => $this->relatedLegacyLandingCards($landingModel),
                'canonicalUrl' => route('leads.landing', $slug),
                'schemaGraph' => $this->buildSchemaGraph($page, route('leads.landing', $slug)),
            ]);
        }

        $nicheModel = LeadNiche::query()->activeOrdered()->where('slug', $slug)->first();
        if ($nicheModel !== null && Schema::hasTable('lead_landing_pages')) {
            $firstLanding = $nicheModel->landingPages()->activeOrdered()->first();
            if ($firstLanding !== null) {
                return redirect()->route('leads.landing', $firstLanding->slug, 301);
            }
        }

        if ($nicheModel !== null) {
            $page = array_merge(MarketingLandingDefaults::shell(), $nicheModel->toPageArray());
            $page['robots_meta'] = $page['robots_meta'] ?? config('leads.default_meta_robots', 'index,follow');
            $page['seo_body'] = $page['seo_body'] ?? '';
            $page['location_label'] = $page['location_label'] ?? '';
            $page['landing_slug'] = $nicheModel->slug;
            $page['niche_slug'] = $nicheModel->slug;
            $page['niche_label'] = $nicheModel->label;

            return $this->renderLeadsShow([
                'slug' => $slug,
                'landingModel' => null,
                'nicheModel' => $nicheModel,
                'marketingLanding' => null,
                'page' => $page,
                'servicesForForm' => $this->servicesForPublicForm(),
                'countriesForForm' => $this->countriesForPublicForm(),
                'defaultServiceId' => $this->serviceIdForSlug($nicheModel->slug),
                'defaultCountryId' => $guessCountryId,
                'guessCountryId' => $guessCountryId,
                'dynamicFormFields' => $this->dynamicFormFields(),
                'relatedLandingCards' => $this->relatedLegacyNicheOnlyCards(),
                'canonicalUrl' => route('leads.landing', $slug),
                'schemaGraph' => $this->buildSchemaGraph($page, route('leads.landing', $slug)),
            ]);
        }

        return response()->view('leads.not-available', [
            'slug' => $slug,
            'metaTitle' => 'Service not available yet',
            'metaDescription' => 'This service category is not available yet. Explore other services or contact us and we will help you find the right option.',
        ], 404);
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function renderLeadsShow(array $payload): View
    {
        $payload['relatedBlogPosts'] = $this->recentPublishedBlogPosts();

        return view('leads.show', $payload);
    }

    /**
     * @return Collection<int, BlogPost>
     */
    private function recentPublishedBlogPosts(): Collection
    {
        if (! Schema::hasTable('blog_posts')) {
            return collect();
        }

        return BlogPost::published()->limit(4)->get(['id', 'slug', 'title', 'published_at']);
    }

    /**
     * @return list<array{url: string, title: string, subtitle: string}>
     */
    private function relatedIndiaLandingPageCards(string $excludeSlug, ?int $preferServiceId = null): array
    {
        $indiaId = $this->indiaCountryIdForRelated();
        if ($indiaId === null || ! Schema::hasTable('landing_pages')) {
            return [];
        }

        $sameService = collect();
        if ($preferServiceId !== null) {
            $sameService = LandingPage::query()
                ->activeOrdered()
                ->with(['service', 'country'])
                ->where('slug', '!=', $excludeSlug)
                ->where('service_id', $preferServiceId)
                ->where('country_id', $indiaId)
                ->limit(6)
                ->get();
        }

        $need = max(0, 12 - $sameService->count());
        $extra = collect();
        if ($need > 0) {
            $q = LandingPage::query()
                ->activeOrdered()
                ->with(['service', 'country'])
                ->where('slug', '!=', $excludeSlug)
                ->where('country_id', $indiaId);
            if ($preferServiceId !== null) {
                $q->where('service_id', '!=', $preferServiceId);
            }
            $extra = $q->inRandomOrder()->limit($need)->get();
        }

        return $sameService->merge($extra)
            ->take(12)
            ->map(function (LandingPage $lp) {
                $svc = $lp->service;

                return [
                    'url' => route('leads.landing', $lp->slug),
                    'title' => $this->relatedCardDisplayTitle($svc !== null ? $svc->name : $lp->slug),
                    'subtitle' => $this->relatedCardSubtitle(),
                ];
            })
            ->all();
    }

    /**
     * Legacy URLs used /leads/{service}-usa; US pages now use /leads/{service} only.
     */
    private function redirectIfLegacyUsaLandingSlug(string $slug): ?RedirectResponse
    {
        if ($slug === '' || ! str_ends_with($slug, '-usa')) {
            return null;
        }

        $short = substr($slug, 0, -4);
        if ($short === '') {
            return null;
        }

        if (! $this->publicLandingSlugExists($short)) {
            return null;
        }

        return redirect()->route('leads.landing', $short, 301);
    }

    private function publicLandingSlugExists(string $slug): bool
    {
        if (Schema::hasTable('landing_pages') && LandingPage::query()->where('slug', $slug)->exists()) {
            return true;
        }

        if (Schema::hasTable('lead_landing_pages') && LeadLandingPage::query()->where('slug', $slug)->exists()) {
            return true;
        }

        return false;
    }

    public function store(StoreMarketingLeadRequest $request): JsonResponse
    {
        if (! Schema::hasTable('marketing_leads')) {
            return response()->json([
                'status' => false,
                'ok' => false,
                'message' => __('Lead capture is not available.'),
            ], 503);
        }

        if (! Schema::hasTable('services') || Service::query()->where('is_active', true)->doesntExist()) {
            return response()->json([
                'status' => false,
                'ok' => false,
                'message' => __('Marketing services are not configured.'),
            ], 503);
        }

        try {
            $validated = $request->validated();
            $country = Country::query()->findOrFail($validated['country_id']);
            $landing = null;
            if (! empty($validated['source_page']) && Schema::hasTable('landing_pages')) {
                $landing = LandingPage::query()->where('slug', $validated['source_page'])->first();
            }

            $allowedExtraKeys = Schema::hasTable('marketing_form_fields')
                ? MarketingFormField::query()->pluck('field_key')->all()
                : [];
            $rawExtra = $validated['extra'] ?? [];
            $extra = [];
            if (is_array($rawExtra)) {
                $extra = array_intersect_key($rawExtra, array_flip($allowedExtraKeys));
            }

            MarketingLead::query()->create([
                'name' => $validated['name'],
                'email' => $validated['email'] ?? null,
                'phone' => $validated['phone'],
                'service_id' => $validated['service_id'],
                'country_id' => $validated['country_id'],
                'country_code' => $country->code,
                'country_name' => $country->name,
                'city' => $validated['city'] ?? null,
                'landing_page_id' => $landing?->id,
                'source_page' => $validated['source_page'] ?? null,
                'utm_source' => $validated['utm_source'] ?? null,
                'utm_medium' => $validated['utm_medium'] ?? null,
                'utm_campaign' => $validated['utm_campaign'] ?? null,
                'extra' => $extra !== [] ? $extra : null,
            ]);
        } catch (Throwable $e) {
            Log::error('Marketing lead could not be saved', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'status' => false,
                'ok' => false,
                'message' => __('Something went wrong. Please try again.'),
            ], 500);
        }

        return response()->json([
            'status' => true,
            'ok' => true,
            'message' => __('Lead submitted successfully!'),
        ]);
    }

    private function guessCountryIdFromRequest(): ?int
    {
        if (! Schema::hasTable('countries')) {
            return null;
        }

        $code = strtoupper((string) request()->header('CF-IPCountry', ''));
        if (strlen($code) === 2) {
            $id = Country::query()->where('code', $code)->where('is_active', true)->value('id');

            return $id !== null ? (int) $id : null;
        }

        return null;
    }

    /**
     * @return Collection<int, Service>
     */
    private function servicesForPublicForm(): Collection
    {
        if (! Schema::hasTable('services')) {
            return collect();
        }

        return Service::query()->activeOrdered()->get(['id', 'name', 'slug']);
    }

    /**
     * @return Collection<int, Country>
     */
    private function countriesForPublicForm(): Collection
    {
        if (! Schema::hasTable('countries')) {
            return collect();
        }

        return Country::query()->activeOrdered()->get(['id', 'code', 'name', 'url_slug']);
    }

    /**
     * @return Collection<int, LeadNiche|Service>
     */
    private function serviceIdForSlug(string $nicheSlug): ?int
    {
        if (! Schema::hasTable('services')) {
            return null;
        }

        $id = Service::query()->where('slug', $nicheSlug)->where('is_active', true)->value('id');

        return $id !== null ? (int) $id : null;
    }

    private function countryIdForUrlSlug(?string $urlSlug): ?int
    {
        if ($urlSlug === null || $urlSlug === '' || ! Schema::hasTable('countries')) {
            return null;
        }

        $id = Country::query()->where('url_slug', $urlSlug)->where('is_active', true)->value('id');

        return $id !== null ? (int) $id : null;
    }

    /**
     * @return Collection<int, MarketingFormField>
     */
    private function dynamicFormFields(): Collection
    {
        if (! Schema::hasTable('marketing_form_fields')) {
            return collect();
        }

        return MarketingFormField::query()->activeOrdered()->get();
    }

    /**
     * @return list<array{url: string, title: string, subtitle: string}>
     */
    private function relatedMarketingLandingCards(LandingPage $current): array
    {
        return $this->relatedIndiaLandingPageCards($current->slug, $current->service_id);
    }

    /**
     * @return list<array{url: string, title: string, subtitle: string}>
     */
    private function relatedLegacyLandingCards(LeadLandingPage $current): array
    {
        if (! Schema::hasTable('lead_landing_pages')) {
            return [];
        }

        $indiaSlug = Country::query()->where('code', 'IN')->where('is_active', true)->value('url_slug');
        if ($indiaSlug === null || $indiaSlug === '') {
            return [];
        }

        return LeadLandingPage::query()
            ->activeOrdered()
            ->with('niche')
            ->where('slug', '!=', $current->slug)
            ->where('location_slug', $indiaSlug)
            ->inRandomOrder()
            ->limit(12)
            ->get()
            ->map(function (LeadLandingPage $lp) {
                $niche = $lp->niche;

                return [
                    'url' => route('leads.landing', $lp->slug),
                    'title' => $this->relatedCardDisplayTitle($niche !== null ? $niche->label : $lp->slug),
                    'subtitle' => $this->relatedCardSubtitle(),
                ];
            })
            ->all();
    }

    /**
     * @return list<array{url: string, title: string, subtitle: string}>
     */
    private function relatedLegacyNicheOnlyCards(): array
    {
        $indiaId = $this->indiaCountryIdForRelated();

        if (Schema::hasTable('landing_pages') && $indiaId !== null) {
            return LandingPage::query()
                ->activeOrdered()
                ->with(['service', 'country'])
                ->where('country_id', $indiaId)
                ->inRandomOrder()
                ->limit(12)
                ->get()
                ->map(function (LandingPage $lp) {
                    return [
                        'url' => route('leads.landing', $lp->slug),
                        'title' => $this->relatedCardDisplayTitle($lp->service?->name ?? ''),
                        'subtitle' => $this->relatedCardSubtitle(),
                    ];
                })
                ->all();
        }

        if (! Schema::hasTable('lead_landing_pages')) {
            return [];
        }

        $indiaSlug = Country::query()->where('code', 'IN')->where('is_active', true)->value('url_slug');
        if ($indiaSlug === null || $indiaSlug === '') {
            return [];
        }

        return LeadLandingPage::query()
            ->activeOrdered()
            ->with('niche')
            ->where('location_slug', $indiaSlug)
            ->inRandomOrder()
            ->limit(12)
            ->get()
            ->map(function (LeadLandingPage $lp) {
                $niche = $lp->niche;

                return [
                    'url' => route('leads.landing', $lp->slug),
                    'title' => $this->relatedCardDisplayTitle($niche !== null ? $niche->label : $lp->slug),
                    'subtitle' => $this->relatedCardSubtitle(),
                ];
            })
            ->all();
    }

    /**
     * Strip trailing " in India" from related-link card titles (section heading stays "Related services in India").
     */
    private function relatedCardDisplayTitle(string $title): string
    {
        $t = trim($title);
        $out = (string) preg_replace('/\s+in\s+India$/iu', '', $t);

        return $out !== '' ? $out : $t;
    }

    private function relatedCardSubtitle(): string
    {
        return __('Apply online');
    }

    private function indiaCountryIdForRelated(): ?int
    {
        if (! Schema::hasTable('countries')) {
            return null;
        }

        $id = Country::query()->where('code', 'IN')->where('is_active', true)->value('id');

        return $id !== null ? (int) $id : null;
    }

    /**
     * @param  array<string, mixed>  $page
     * @return array<string, mixed>
     */
    private function buildSchemaGraph(array $page, string $canonicalUrl): array
    {
        $graph = [];
        $biz = config('leads.schema', []);
        $businessName = $biz['business_name'] ?? config('app.name');
        $orgUrl = $biz['url'] ?? config('app.url');
        $orgId = $orgUrl.'#organization';

        $street = $biz['street'] ?? null;
        $locality = $biz['locality'] ?? null;
        $region = $biz['region'] ?? null;
        $postal = $biz['postal_code'] ?? null;
        $country = $biz['country'] ?? null;
        $hasPostalAddress = filled($street) && filled($locality) && filled($country);

        if ($hasPostalAddress) {
            $graph[] = [
                '@type' => 'LocalBusiness',
                '@id' => $orgId,
                'name' => $businessName,
                'url' => $orgUrl,
                'telephone' => $biz['phone'] ?? null,
                'image' => asset('front/images/logo.png'),
                'address' => [
                    '@type' => 'PostalAddress',
                    'streetAddress' => $street,
                    'addressLocality' => $locality,
                    'addressRegion' => $region,
                    'postalCode' => $postal,
                    'addressCountry' => $country,
                ],
            ];
        } else {
            $graph[] = [
                '@type' => 'Organization',
                '@id' => $orgId,
                'name' => $businessName,
                'url' => $orgUrl,
                'logo' => asset('front/images/logo.png'),
            ];
        }

        $faqEntities = [];
        $faqs = is_array($page['faqs'] ?? null) ? $page['faqs'] : [];
        foreach ($faqs as $item) {
            if (! is_array($item)) {
                continue;
            }
            $q = $item['q'] ?? $item['question'] ?? null;
            $a = $item['a'] ?? $item['answer'] ?? null;
            if (! is_string($q) || $q === '' || ! is_string($a) || $a === '') {
                continue;
            }
            $faqEntities[] = [
                '@type' => 'Question',
                'name' => $q,
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => $a,
                ],
            ];
        }
        if ($faqEntities !== []) {
            $graph[] = [
                '@type' => 'FAQPage',
                'mainEntity' => $faqEntities,
            ];
        }

        $nicheLabel = trim((string) ($page['niche_label'] ?? ''));
        $serviceName = $nicheLabel !== '' ? $nicheLabel : trim((string) ($page['meta_title'] ?? 'Service'));
        $service = [
            '@type' => 'Service',
            'name' => $serviceName !== '' ? $serviceName : 'Service',
            'description' => $page['meta_description'] ?? '',
            'url' => $canonicalUrl,
            'provider' => ['@id' => $orgId],
        ];
        // Google policy: only output AggregateRating when backed by real, visible reviews on the page;
        // never fabricate ratings for rich results. Keys come from admin/content JSON when you have data.
        $rVal = $page['aggregate_rating_value'] ?? null;
        $rCount = $page['aggregate_rating_count'] ?? null;
        if (is_numeric($rVal) && is_numeric($rCount) && (float) $rVal > 0 && (int) $rCount > 0) {
            $service['aggregateRating'] = [
                '@type' => 'AggregateRating',
                'ratingValue' => (float) $rVal,
                'ratingCount' => (int) $rCount,
                'bestRating' => 5,
                'worstRating' => 1,
            ];
        }
        $graph[] = $service;

        return [
            '@context' => 'https://schema.org',
            '@graph' => $graph,
        ];
    }
}
