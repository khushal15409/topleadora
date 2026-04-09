# WP-CRM

Multi-tenant CRM + marketing lead capture built with Laravel.

## What this app includes
- Public marketing lead capture pages
  - View landing pages: `GET /leads/{slug}`
  - Submit leads/quotes: `POST /leads` and `POST /get-quote`
- CRM dashboard (organization accounts)
  - Leads: `/dashboard/leads`
  - Pipeline stages: `/dashboard/pipeline`
  - Follow-ups: `/dashboard/followups`
  - WhatsApp broadcast: `/dashboard/broadcast`
  - Reports/analytics: `/dashboard/reports`
- Admin area (super admin)
  - Organizations & onboarding
  - Role/permission management (RBAC)
  - Marketing configuration (services, countries, landing pages, form fields)
  - Broadcast usage, analytics, revenue, subscriptions monitoring
- Integrations
  - WhatsApp Cloud API
    - Webhook verify/receive: `GET/POST /webhooks/whatsapp`
    - Outbound messaging via broadcast
  - Razorpay subscription billing
    - Webhook: `POST /webhooks/razorpay`
- SEO helpers
  - `GET /robots.txt`, `GET /sitemap.xml`
  - Additional sitemaps: `sitemap-main.xml`, `sitemap-blog.xml`, `sitemap-leads.xml`

## Tech Stack
- PHP 8.2+
- Laravel 12
- Vite (Tailwind/SASS)
- Database: SQLite by default (see `.env`), MySQL supported
- Key packages: `spatie/laravel-permission`, `razorpay/razorpay`

## Requirements
- PHP + Composer
- Node.js + npm (for Vite assets)
- A database (SQLite/MySQL) configured in `.env`

## Local Setup (recommended)
1. Install backend dependencies:
   - `composer install`
2. Setup environment:
   - `cp .env.example .env`
   - `php artisan key:generate`
3. Install frontend dependencies:
   - `npm install`
4. Create database tables:
   - `php artisan migrate --force`
5. Seed demo data (creates an admin and a demo organization/users):
   - `php artisan db:seed --force`
6. Build frontend assets:
   - `npm run build`

## Run
- Full local development (web + queue + Vite):
  - `composer run dev`
- Laravel only:
  - `php artisan serve`
  - Vite (separate terminal): `npm run dev`

## Demo credentials
After running `php artisan db:seed --force`:
- Super Admin: `test@example.com` / `password`
- Organization user: `org@example.com` / `password`

## Integration configuration (DB-backed settings)
WhatsApp + Razorpay secrets are not stored in `.env`; they are read from the database via the `setting()` helper (cached in-memory).

Common keys you may need to set in the admin settings UI (or seed them into the `settings` table):
- Monetization toggle:
  - `payment_enabled` (set to `0` to run in FREE mode)
- WhatsApp Cloud API:
  - Token: `whatsapp_token` or `integrations.whatsapp.api_token`
  - Phone number id: `phone_number_id` or `integrations.whatsapp.phone_number_id`
  - Webhook verify token: `webhook_verify_token` or `integrations.whatsapp.webhook_verify_token`
  - Inbound organization id: `integrations.whatsapp.inbound_organization_id`
- Razorpay:
  - `razorpay_key`
  - `razorpay_secret`

## Notes
- Webhook endpoints will respond with errors if the related integration settings are not configured.
- Production deployments should also run Laravel config/routes/view caching as needed.

## License
MIT (Laravel framework base)

