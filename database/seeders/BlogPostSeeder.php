<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BlogPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $posts = [
            [
                'title' => 'Best WhatsApp CRM for Real Estate Agents in India',
                'excerpt' => 'A practical guide to choosing the best WhatsApp CRM in India for brokers and real estate sales teams: lead capture, pipeline, and follow-ups.',
                'image' => 'front/images/landify/features/features-1.webp',
                'meta_title' => 'Best WhatsApp CRM for Real Estate Agents in India',
                'meta_description' => 'Discover the best WhatsApp CRM software in India for real estate. Learn how a lead management system improves follow-ups and conversions.',
                'body' => $this->bodyRealEstate(),
            ],
            [
                'title' => 'How to Manage Leads on WhatsApp',
                'excerpt' => 'Stop losing inquiries in chats. Learn a simple WhatsApp lead management system: capture, assign, follow up, and close deals.',
                'image' => 'front/images/landify/features/features-2.webp',
                'meta_title' => 'How to Manage Leads on WhatsApp (WhatsApp CRM Guide)',
                'meta_description' => 'Learn how to manage leads on WhatsApp using a CRM: pipeline stages, follow-ups, and templates to convert faster.',
                'body' => $this->bodyManageLeads(),
            ],
            [
                'title' => 'Top CRM Software for Sales Teams',
                'excerpt' => 'What sales teams need from a modern CRM: lead capture, pipeline visibility, follow-ups, reporting, and automation.',
                'image' => 'front/images/landify/features/features-4.webp',
                'meta_title' => 'Top CRM Software for Sales Teams (India)',
                'meta_description' => 'A checklist for choosing CRM software for sales teams: pipeline, follow-ups, reporting, and lead management best practices.',
                'body' => $this->bodySalesTeams(),
            ],
            [
                'title' => 'How CRM Improves Sales Conversion',
                'excerpt' => 'Why conversion improves when leads are assigned, followed up on time, and moved through a clear pipeline.',
                'image' => 'front/images/landify/illustration/illustration-15.webp',
                'meta_title' => 'How CRM Improves Sales Conversion',
                'meta_description' => 'Understand how a WhatsApp CRM and lead management system improves sales conversion with follow-ups and pipeline discipline.',
                'body' => $this->bodyConversion(),
            ],
            [
                'title' => 'Lead Management Guide for Beginners',
                'excerpt' => 'A beginner-friendly guide to lead management: sources, stages, follow-ups, and simple reporting.',
                'image' => 'front/images/landify/features/features-3.webp',
                'meta_title' => 'Lead Management Guide for Beginners',
                'meta_description' => 'A complete lead management guide: capture leads, assign owners, follow up, and close using a simple CRM.',
                'body' => $this->bodyBeginners(),
            ],
            [
                'title' => 'Why Every Business Needs CRM',
                'excerpt' => 'A CRM is not just a database. It is a system for ownership, follow-ups, pipeline, and predictable growth.',
                'image' => 'front/images/landify/features/features-5.webp',
                'meta_title' => 'Why Every Business Needs CRM (WhatsApp CRM)',
                'meta_description' => 'Learn why every business needs a CRM: lead management system, pipeline visibility, follow-ups, and better conversion.',
                'body' => $this->bodyWhyCrm(),
            ],
        ];

        foreach ($posts as $i => $data) {
            $slug = Str::slug($data['title']);
            BlogPost::updateOrCreate(
                ['slug' => $slug],
                array_merge($data, [
                    'slug' => $slug,
                    'published_at' => now()->subDays(2 + $i),
                    'is_published' => true,
                ])
            );
        }
    }

    private function bodyRealEstate(): string
    {
        $pricing = route('pricing');
        $features = route('features');
        $home = url('/');

        return <<<HTML
<p><strong>Real estate leads move fast on WhatsApp.</strong> A buyer asks for a brochure, a site visit, loan eligibility, or a price break. If you miss the follow-up by a few hours, the lead goes to another broker. That’s why high-performing teams in India use a <em>WhatsApp CRM</em> (a lead management system built around WhatsApp workflows) instead of relying on chats + spreadsheets.</p>

<h2>What “best WhatsApp CRM” means for real estate agents in India</h2>
<p>The best WhatsApp CRM software in India for brokers is not the one with the most features. It’s the one that makes daily execution simple: capture every inquiry, keep the pipeline visible, and make follow-ups impossible to forget. If your team has to “remember” what to do next, the CRM is failing.</p>

<h2>1) Capture every lead (without losing chats)</h2>
<p>WhatsApp is noisy. Inquiries arrive from ads, Instagram DMs, website forms, referrals, and inbound calls—then the conversation shifts to WhatsApp. A good lead management system stores the basics in one place: <strong>name</strong>, <strong>phone</strong>, <strong>source</strong>, <strong>status</strong>, <strong>notes</strong>, and <strong>next follow-up date</strong>. When your team uses multiple phones or multiple numbers, central tracking becomes essential.</p>
<p><strong>Best practice:</strong> create the lead record during the first response. If you wait, you will forget context (property, location, budget, move-in timeline) and the lead becomes “just another chat”.</p>

<h2>2) Use a simple pipeline (Kanban stages)</h2>
<p>Real estate pipelines don’t need 20 stages. Over-precision creates inconsistent reporting. Keep it simple:</p>
<ul>
  <li><strong>New</strong> — fresh inquiry, not contacted</li>
  <li><strong>Contacted</strong> — first response sent / call done</li>
  <li><strong>Interested</strong> — shared options, budget confirmed, shortlist started</li>
  <li><strong>Follow-up</strong> — waiting on decision, site visit planned, documents pending</li>
  <li><strong>Closed</strong> — booked / converted (or clearly not interested)</li>
</ul>
<p>A WhatsApp CRM should let you move leads with one action (drag-and-drop or a quick status change). Managers should be able to open the pipeline and understand the funnel in 10 seconds.</p>

<h2>3) Follow-ups that drive conversions</h2>
<p>Most deals are won by the team that follows up consistently. A follow-up is not “I’ll message later”. It’s a scheduled next step with a date/time. The best CRMs show:</p>
<ul>
  <li><strong>Today</strong> — calls and messages due now</li>
  <li><strong>Upcoming</strong> — future scheduled follow-ups</li>
  <li><strong>Completed</strong> — closed loop actions for accountability</li>
</ul>
<p>This is where conversion improves: you stop forgetting, you stop duplicating efforts, and you build predictable momentum.</p>

<h2>4) Notes that actually help</h2>
<p>Notes should capture decision context, not a long story. A good pattern is: <em>need + constraint + next step</em>. Examples: “2BHK, Whitefield, budget 85L, wants visit Sat 11am” or “Investor, prefers ready-to-move, needs rental estimate, follow-up tomorrow”.</p>

<h2>5) Broadcast responsibly (without spamming)</h2>
<p>Broadcasts help re-engage: price drops, new inventory in a locality, open-house reminders, new launches. The key is segmentation. Send to a list of relevant leads, not everyone. Save broadcast history so managers can track usage and results per organization.</p>

<h2>How to choose the right WhatsApp CRM</h2>
<p>Before you buy, answer these questions:</p>
<ul>
  <li>Can our team add a lead in under 30 seconds?</li>
  <li>Can we search by name/phone and filter by status quickly?</li>
  <li>Can we see next follow-up dates for all leads?</li>
  <li>Can we track conversions (Closed) and follow-up completion?</li>
  <li>Does it support a clean pipeline for the whole team?</li>
  <li>Can we broadcast and track usage per organization?</li>
</ul>

<h2>Next steps</h2>
<p>If you want a clean, simple WhatsApp CRM built for sales teams, start here:</p>
<ul>
  <li><a href="{$features}">Features</a> – see what’s included</li>
  <li><a href="{$pricing}">Pricing</a> – start a free trial</li>
  <li><a href="{$home}">Home</a> – overview of WhatsApp CRM</li>
</ul>

<p><strong>Bottom line:</strong> the best WhatsApp CRM for real estate agents in India is the one your team actually uses daily—because it is fast, simple, and built around pipeline + follow-ups.</p>
HTML;
    }

    private function bodyManageLeads(): string
    {
        $pricing = route('pricing');
        $blog = route('blog.index');

        return <<<HTML
<p>WhatsApp is where leads start—but without a <strong>lead management system</strong>, most teams lose context, forget follow-ups, and can’t measure performance. This guide shows a simple process to manage leads on WhatsApp using a CRM (and why it works in India across real estate, education, clinics, agencies, and B2B).</p>

<h2>Step 1: Capture the lead immediately</h2>
<p>When a new message comes in, create a lead record with <strong>Name</strong>, <strong>Phone</strong>, <strong>Source</strong>, and a short note. If you wait, the chat gets buried under new messages. A WhatsApp CRM makes this quick: you add the lead, select source from a dropdown, and set a default stage.</p>

<h2>Step 2: Assign ownership (no owner = no follow-up)</h2>
<p>Every lead must have an owner. If ownership is unclear, response time slows and accountability disappears. Assignment also makes reporting accurate: managers can see who is following up and who needs support.</p>

<h2>Step 3: Move through a simple pipeline</h2>
<p>Use consistent stages: <strong>New</strong> → <strong>Contacted</strong> → <strong>Interested</strong> → <strong>Follow-up</strong> → <strong>Closed</strong>. The goal is not perfect categorization—the goal is momentum. In a CRM, the pipeline becomes a shared language for the team.</p>
<p><strong>Tip:</strong> decide one definition per stage. Example: “Contacted” means first reply sent + call attempted; “Interested” means shared options/pricing; “Follow-up” means waiting for decision; “Closed” means bought or clearly not buying.</p>

<h2>Step 4: Schedule the next follow-up date</h2>
<p>Follow-ups are the difference between “inquiry” and “deal”. After every interaction, set the next follow-up date. If there is no next step, the lead is drifting. A follow-up date creates a daily worklist and improves conversions.</p>

<h2>Step 5: Add short, useful notes</h2>
<p>Notes should capture decision context: budget, requirement, constraints, and what you promised. Keep notes short and actionable. You can also add notes quickly via a modal so you don’t leave the lead list.</p>

<h2>Templates that help (without sounding robotic)</h2>
<p>Use templates as a starting point, then personalize 1–2 lines:</p>
<ul>
  <li>“Sharing details now. What’s your preferred time for a quick call today?”</li>
  <li>“Following up—did you get a chance to review the options?”</li>
  <li>“Just checking: should I keep this open for you or close it for now?”</li>
</ul>

<h2>Step 6: Use broadcasts for warm follow-ups</h2>
<p>Broadcasts work when they are relevant. Send updates to a targeted group: people who asked about a locality, product, or plan. Save history so you can track what was sent and when.</p>

<h2>Start simple, then scale</h2>
<p>As your team grows, the process stays the same. What changes is visibility: managers can see follow-ups pending and pipeline health across the organization. That is the real value of a WhatsApp CRM.</p>

<p>Ready to try it? <a href="{$pricing}">Start Free Trial</a> or read more guides on the <a href="{$blog}">Blog</a>.</p>
HTML;
    }

    private function bodySalesTeams(): string
    {
        $features = route('features');

        return <<<HTML
<p>Sales teams don’t need complicated CRMs. They need a system that keeps the pipeline honest and follow-ups consistent. This checklist helps you choose top CRM software for sales teams in India—especially if a big part of your selling happens on WhatsApp.</p>

<h2>What matters most for a sales CRM</h2>
<ul>
  <li><strong>Lead capture</strong> from WhatsApp, web forms, ads, and social</li>
  <li><strong>Pipeline</strong> visibility with clean stages</li>
  <li><strong>Follow-ups</strong> with due dates (Today/Upcoming/Completed)</li>
  <li><strong>Broadcast</strong> for re-engagement with history tracking</li>
  <li><strong>Reports</strong> that show growth and stage distribution</li>
</ul>

<h2>Simple pipeline stages win</h2>
<p>Too many stages create confusion and inconsistent reporting. A CRM for sales teams should default to a small number of stages and make it easy to move leads quickly.</p>

<h2>Why WhatsApp CRM is becoming essential</h2>
<p>In India, a large portion of sales happens on WhatsApp. The best CRMs connect daily WhatsApp operations with structured lead data so teams can scale without losing accountability.</p>

<h2>How to evaluate a CRM quickly</h2>
<p>Run a 30-minute test with a real rep:</p>
<ol>
  <li>Add a lead (name, phone, source, note).</li>
  <li>Set status and next follow-up date.</li>
  <li>Move the lead in the pipeline.</li>
  <li>Find it again using search.</li>
  <li>Mark follow-up complete.</li>
</ol>
<p>If those actions are not fast, adoption will fail.</p>

<h2>Recommended approach</h2>
<p>Start with a lightweight WhatsApp CRM that supports lead management, pipeline, and follow-ups. See <a href="{$features}">Features</a> for what’s included.</p>
HTML;
    }

    private function bodyConversion(): string
    {
        $home = url('/');

        return <<<HTML
<p>Sales conversion improves when your process becomes visible: who owns the lead, what stage it’s in, and when the next follow-up happens. That’s exactly what a CRM enforces.</p>

<h2>1) Faster response time</h2>
<p>When leads are captured immediately, response time drops. Faster response increases trust and keeps the prospect engaged.</p>

<h2>2) Better follow-up discipline</h2>
<p>A follow-up date is a commitment. CRMs create a “Today” list so reps don’t forget, and managers can coach on execution.</p>

<h2>3) Pipeline clarity</h2>
<p>Pipeline stages help teams forecast. Even a simple Kanban board creates clarity: what is new, what is moving, and what is stuck.</p>

<h2>4) Clean handoffs</h2>
<p>When notes and next steps are logged, a lead can be reassigned without losing context. That consistency improves conversion across teams.</p>

<p>Want to implement this fast? Start from <a href="{$home}">Home</a> and open the dashboard.</p>
HTML;
    }

    private function bodyBeginners(): string
    {
        $pricing = route('pricing');

        return <<<HTML
<p>If you are new to lead management, keep it simple. Your goal is to turn inquiries into scheduled next steps. This guide explains the basics in a beginner-friendly way.</p>

<h2>What is a lead?</h2>
<p>A lead is a person or business that might buy from you. Leads usually come from WhatsApp, calls, Instagram, referrals, and your website.</p>

<h2>The 5 essentials</h2>
<ol>
  <li>Capture name + phone</li>
  <li>Track the source</li>
  <li>Assign an owner</li>
  <li>Use a simple stage</li>
  <li>Set next follow-up date</li>
</ol>

<h2>How to build a habit</h2>
<p>After every interaction, update status and follow-up. If you do just these two actions consistently, your conversions improve.</p>

<p>Try it with a simple tool: <a href="{$pricing}">Start Free Trial</a>.</p>
HTML;
    }

    private function bodyWhyCrm(): string
    {
        $features = route('features');
        $blog = route('blog.index');

        return <<<HTML
<p>Every business needs a CRM because growth creates complexity. Without a lead management system, you rely on memory and scattered chats. With a CRM, you build a repeatable process.</p>

<h2>CRM is a process, not a database</h2>
<p>A CRM helps teams follow the same steps: capture, assign, follow up, move stages, and close. The tool makes the process visible and measurable.</p>

<h2>WhatsApp makes CRM even more important</h2>
<p>WhatsApp is fast—but chats are not structured. A WhatsApp CRM connects conversations to pipeline stages and follow-ups.</p>

<h2>What to do next</h2>
<ul>
  <li>Review <a href="{$features}">Features</a></li>
  <li>Read more on the <a href="{$blog}">Blog</a></li>
</ul>
HTML;
    }
}
