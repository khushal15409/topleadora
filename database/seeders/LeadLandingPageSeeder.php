<?php

namespace Database\Seeders;

use App\Models\LeadLandingPage;
use App\Models\LeadNiche;
use App\Support\LeadLandingSeoGenerator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class LeadLandingPageSeeder extends Seeder
{
    /**
     * @return array<string, string>
     */
    private function locations(): array
    {
        return [
            'usa' => 'United States',
            'india' => 'India',
            'uk' => 'United Kingdom',
            'uae' => 'United Arab Emirates',
            'canada' => 'Canada',
        ];
    }

    public function run(): void
    {
        $sort = 0;
        foreach (LeadNiche::query()->orderBy('sort_order')->orderBy('label')->get() as $niche) {
            foreach ($this->locations() as $locSlug => $locLabel) {
                $slug = $locSlug === 'usa' ? $niche->slug : $niche->slug.'-'.$locSlug;
                $focus = LeadLandingSeoGenerator::focusKeywordsForNiche($niche->slug);
                $keywords = implode(', ', array_merge($focus, [$locLabel, $niche->label]));
                $metaTitle = $this->highCtrTitle($niche->slug, $niche->label, $locLabel);
                $metaDescription = LeadLandingSeoGenerator::metaDescription($niche, $locLabel, $focus);
                $heroHeadline = $this->heroHeadline($niche->slug, $niche->label, $locLabel);
                $heroSub = sprintf(
                    'Compare vetted %s partners serving %s. Free quote — quick callback, no spam.',
                    Str::lower($niche->label),
                    $locLabel
                );

                LeadLandingPage::query()->updateOrCreate(
                    ['slug' => $slug],
                    [
                        'lead_niche_id' => $niche->id,
                        'location_slug' => $locSlug,
                        'location_label' => $locLabel,
                        'meta_title' => $metaTitle,
                        'meta_description' => Str::limit($metaDescription, 320, ''),
                        'meta_keywords' => Str::limit($keywords, 512, ''),
                        'robots_meta' => 'index,follow',
                        'hero_headline_override' => $heroHeadline,
                        'hero_subheadline_override' => $heroSub,
                        'seo_body' => LeadLandingSeoGenerator::bodyHtml($niche, $locLabel, $focus),
                        'aggregate_rating_value' => 4.7,
                        'aggregate_rating_count' => 2840 + ($sort % 200),
                        'is_active' => true,
                        'sort_order' => $sort,
                    ]
                );
                $sort++;
            }
        }
    }

    private function highCtrTitle(string $nicheSlug, string $label, string $locLabel): string
    {
        return match ($nicheSlug) {
            'loan' => "Get Instant Loan Help in {$locLabel} — Apply Free Today",
            'insurance' => "Compare {$label} in {$locLabel} — Save Time & Money",
            'real-estate' => "{$label} Experts in {$locLabel} — Book a Free Consultation",
            'solar' => "Cut Energy Bills in {$locLabel} — Solar Quotes in Minutes",
            'share-market' => "Start Investing in {$locLabel} — Guided Matching",
            'education' => "Study & Admissions Help for {$locLabel} — Talk to Experts",
            'home-services' => "Trusted Home Services in {$locLabel} — Book Fast",
            'digital-services' => "Growth & Web Services in {$locLabel} — Get a Quote",
            'health-wellness' => "{$label} Programs in {$locLabel} — Start Today",
            'legal-services' => "{$label} in {$locLabel} — Confidential Intake",
            'credit-repair' => "Fix Credit Fast in {$locLabel} — Free Assessment",
            'car-services' => "Auto Loans & Coverage in {$locLabel} — Compare Now",
            default => "{$label} in {$locLabel} — Trusted Partners & Free Quote",
        };
    }

    private function heroHeadline(string $nicheSlug, string $label, string $locLabel): string
    {
        return match ($nicheSlug) {
            'loan' => "Personal loans & fast approvals in {$locLabel}",
            'insurance' => "{$label} you can compare with confidence in {$locLabel}",
            'share-market' => "Start investing smarter in {$locLabel}",
            default => "{$label} in {$locLabel} — matched to trusted partners",
        };
    }
}
