<?php

namespace App\Support;

use App\Models\LeadNiche;

class LeadLandingSeoGenerator
{
    /**
     * @param  list<string>  $focusKeywords
     */
    public static function metaTitle(LeadNiche $niche, string $locationLabel, string $appName): string
    {
        return sprintf(
            '%s in %s — Apply Free | Fast Matching | %s',
            $niche->label,
            $locationLabel,
            $appName
        );
    }

    /**
     * @param  list<string>  $focusKeywords
     */
    public static function metaDescription(LeadNiche $niche, string $locationLabel, array $focusKeywords): string
    {
        $kw = $focusKeywords[0] ?? $niche->label;

        return sprintf(
            'Get matched for %s in %s. Compare trusted partners, save time, and move forward with clarity. %s. Free quote, quick callbacks.',
            $niche->label,
            $locationLabel,
            $kw,
        );
    }

    /**
     * Long-form HTML (target 800+ words). Uses H2/H3 only — page H1 comes from hero.
     *
     * @param  list<string>  $focusKeywords
     */
    public static function bodyHtml(LeadNiche $niche, string $locationLabel, array $focusKeywords): string
    {
        $label = htmlspecialchars((string) $niche->label, ENT_QUOTES, 'UTF-8');
        $loc = htmlspecialchars($locationLabel, ENT_QUOTES, 'UTF-8');
        $k1 = htmlspecialchars($focusKeywords[0] ?? $label, ENT_QUOTES, 'UTF-8');
        $k2 = htmlspecialchars($focusKeywords[1] ?? $k1, ENT_QUOTES, 'UTF-8');
        $k3 = htmlspecialchars($focusKeywords[2] ?? $k2, ENT_QUOTES, 'UTF-8');

        $p = static function (string $text): string {
            return '<p class="mb-3 lh-lg">'.$text.'</p>';
        };

        $html = '';
        $html .= '<h2 class="h4 fw-bold mt-2 mb-3">'.$label.' in '.$loc.': what this page helps you solve</h2>';
        $html .= $p(
            "If you are researching <strong>{$label}</strong> specifically for people in <strong>{$loc}</strong>, you are in the right place. "
            .'Most buyers stall because information is scattered, timelines are unclear, and it is hard to know who is actually qualified to help. '
            .'Our role is simple: collect your details once, route you to relevant partners, and help you compare next steps without pressure tactics.'
        );
        $html .= $p(
            "Across {$loc}, demand changes by season, local regulations, and partner availability. That is why a one-size message rarely works. "
            ."This guide explains how matching works, what documents you may need, typical timelines, and how to avoid common mistakes — especially around <em>{$k1}</em> and <em>{$k2}</em>."
        );

        $html .= '<h3 class="h5 fw-bold mt-4 mb-3">Why search intent matters for '.$k1.'</h3>';
        $html .= $p(
            "People who search for <strong>{$k1}</strong> usually want speed, transparency, and a realistic plan — not vague promises. "
            .'Search engines rank pages that answer intent, show expertise, and link to useful next steps. That is why we keep language practical: what you should prepare, what questions to ask, and what “good” looks like at each stage.'
        );
        $html .= $p(
            "If you are also comparing <strong>{$k3}</strong>, write that in your message after you submit the form. It helps partners route you to the right specialist on the first call."
        );

        $html .= '<h3 class="h5 fw-bold mt-4 mb-3">Typical process (simple, step-by-step)</h3>';
        $html .= '<ol class="mb-4 ps-3 lh-lg">';
        $html .= '<li class="mb-2"><strong>Short intake:</strong> basics about your goal, city, timeline, and budget band.</li>';
        $html .= '<li class="mb-2"><strong>Matching:</strong> we introduce partners that actively serve customers in '.$loc.'.</li>';
        $html .= '<li class="mb-2"><strong>Comparison:</strong> you review options, fees, timelines, and responsibilities before you commit.</li>';
        $html .= '<li class="mb-2"><strong>Execution:</strong> paperwork, verification, and onboarding happen with the partner you choose.</li>';
        $html .= '</ol>';

        $html .= '<h3 class="h5 fw-bold mt-4 mb-3">Documents and readiness checklist</h3>';
        $html .= $p(
            'Requirements vary by product and partner. Still, most successful applications start with identity proof, address proof, income or business evidence (where applicable), and a clear statement of what you want to achieve in the next 30–90 days.'
        );
        $html .= $p(
            'If you are a returning applicant, mention any prior applications (approved or declined) so partners avoid duplicate checks that can slow you down.'
        );

        $html .= '<h3 class="h5 fw-bold mt-4 mb-3">Local notes for '.$loc.'</h3>';
        $html .= $p(
            "Eligibility, taxation, licencing, and consumer protections can differ by region. Partners serving {$loc} should explain local constraints in plain English. "
            .'If something sounds too good to be true — guaranteed approvals, “delete all credit history”, or instant refunds — treat it as a red flag and verify independently.'
        );

        $html .= '<h3 class="h5 fw-bold mt-4 mb-3">FAQs people ask before they apply</h3>';
        $html .= $p(
            '<strong>How fast is the first callback?</strong> Many requests receive a response within one business day, but peak seasons can add delay.'
        );
        $html .= $p(
            '<strong>Is there a fee to submit this form?</strong> Submitting interest here is free. Any professional fees depend on the partner and service you choose.'
        );
        $html .= $p(
            '<strong>Will this hurt my credit or profile?</strong> It depends on the product. Partners should tell you before any hard check or formal submission.'
        );

        $html .= '<h3 class="h5 fw-bold mt-4 mb-3">Related topics you may be comparing</h3>';
        $html .= $p(
            "Many visitors researching <strong>{$label}</strong> also compare fees, service speed, post-sale support, and contract terms. "
            .'Use the short message box to list must-haves — it increases match quality substantially.'
        );

        $html .= '<h3 class="h5 fw-bold mt-4 mb-3">Trust, safety, and privacy</h3>';
        $html .= $p(
            'Do not share OTPs, passwords, or full card numbers in free-text fields. Legitimate partners will never ask you to bypass normal security steps.'
        );
        $html .= $p(
            'We aim to keep this page updated so it remains useful for both humans and search engines. If a policy changes in your region, partners should disclose that during consultation.'
        );

        $html .= '<h3 class="h5 fw-bold mt-4 mb-3">Bottom line</h3>';
        $html .= $p(
            "If you want {$label} support in {$loc} with a clearer path forward, submit the form above. "
            ."It is the fastest way to turn research into action — especially if you care about {$k1}, {$k2}, and {$k3}."
        );

        $html .= '<h2 class="h4 fw-bold mt-5 mb-3">How to compare offers without feeling overwhelmed</h2>';
        $html .= $p(
            "Decision paralysis is common when every provider claims to be the cheapest or the fastest. Start by listing your non‑negotiables: timeline, maximum fees you can accept, service hours, language support, and whether you need in‑person help in {$loc}. "
            .'Then compare only the subset that meets those constraints. This single step removes most noise and keeps your evaluation fair.'
        );
        $html .= $p(
            "When you research {$k1} alongside {$k2}, write pros and cons in a simple table. Numbers beat adjectives: ask for illustrative examples, ranges, and what happens if timelines slip. "
            .'If a provider refuses clarity on basics, treat that as signal — not drama — and keep looking.'
        );

        $html .= '<h3 class="h5 fw-bold mt-4 mb-3">Mistakes that quietly delay progress</h3>';
        $html .= $p(
            'The most expensive mistake is waiting for a “perfect moment.” Markets, promos, and partner capacity shift; a reasonable plan today often beats a flawless plan next quarter. '
            .'A second mistake is mixing incomplete applications across multiple channels, which can create duplicate records and slower responses.'
        );
        $html .= $p(
            'A third mistake is hiding material facts to “test” eligibility. In regulated categories, disclosures matter; surprises later can reset timelines or close doors entirely.'
        );

        $html .= '<h3 class="h5 fw-bold mt-4 mb-3">Budget, cash flow, and monthly impact</h3>';
        $html .= $p(
            "Even when {$label} is the right category for your goal, the affordability check still matters. Build a conservative monthly buffer after essentials, then stress‑test it with a 10–15% downside scenario. "
            .'If you are uncertain, mention your comfort range in the form message so a specialist can anchor recommendations realistically.'
        );
        $html .= $p(
            "If you are optimizing for {$k3}, ask how the numbers change at different commitment levels — small adjustments often unlock materially better outcomes without extra risk."
        );

        $html .= '<h3 class="h5 fw-bold mt-4 mb-3">When to get a second opinion</h3>';
        $html .= $p(
            "If you receive advice that feels coercive (“sign today or lose the offer”), pause. Legitimate teams in {$loc} can explain trade‑offs, put terms in writing, and give you space to decide. "
            .'A second opinion is especially wise when the proposed structure is complex, fees are opaque, or you are asked to bypass standard verification.'
        );

        $html .= '<h3 class="h5 fw-bold mt-4 mb-3">Glossary — terms you may hear on the first call</h3>';
        $html .= '<ul class="mb-4 lh-lg">';
        $html .= '<li class="mb-2"><strong>Eligibility:</strong>whether you meet baseline rules for a product or service.</li>';
        $html .= '<li class="mb-2"><strong>Verification:</strong>checks that confirm identity, income, or ownership.</li>';
        $html .= '<li class="mb-2"><strong>Cooling‑off period:</strong>a window where you can reconsider certain agreements (varies by product and region).</li>';
        $html .= '<li class="mb-2"><strong>SLA:</strong>a service timeline expectation between you and the provider.</li>';
        $html .= '</ul>';

        $html .= '<h3 class="h5 fw-bold mt-4 mb-3">What happens after you submit this page</h3>';
        $html .= $p(
            "Your request is routed based on {$label}, {$loc}, and the details you provide. Partners may reach you by phone or email depending on your preference and local norms. "
            ."If you add context about {$k1} or {$k2}, routing quality improves and the first conversation tends to be more productive."
        );
        $html .= $p(
            'Save this page URL if you want to revisit the guide later; bookmarking also helps if you run ads and want to compare sessions over time.'
        );

        return $html;
    }

    /**
     * @return list<string>
     */
    public static function focusKeywordsForNiche(string $nicheSlug): array
    {
        return match ($nicheSlug) {
            'loan' => ['personal loan approval', 'instant loan help', 'low interest loan comparison'],
            'insurance' => ['cheap health insurance', 'best term life insurance', 'family coverage plans'],
            'real-estate' => ['buy property guide', 'home investment tips', 'NRI property support'],
            'solar' => ['rooftop solar savings', 'solar subsidy information', 'solar net metering'],
            'share-market' => ['start investing today', 'best trading platform for beginners', 'SIP planning help'],
            'education' => ['study abroad counseling', 'IELTS and admissions', 'scholarship planning'],
            'home-services' => ['deep cleaning booking', 'pest control service', 'AC repair near me'],
            'digital-services' => ['website development agency', 'SEO services company', 'conversion landing pages'],
            'health-wellness' => ['weight loss coaching', 'fitness program online', 'sustainable diet plan'],
            'legal-services' => ['injury claim help', 'immigration lawyer intake', 'legal consultation matching'],
            'credit-repair' => ['credit repair USA', 'debt relief options', 'dispute inaccurate credit items'],
            'car-services' => ['car loan approval', 'cheap car insurance compare', 'used car inspection checklist'],
            default => ['trusted partners', 'fast application help', 'transparent pricing'],
        };
    }
}
