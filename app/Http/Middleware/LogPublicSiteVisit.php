<?php

namespace App\Http\Middleware;

use App\Models\SiteVisit;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class LogPublicSiteVisit
{
    /**
     * Log anonymous/public marketing site page views after the response is sent.
     */
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }

    public function terminate(Request $request, Response $response): void
    {
        if ($request->method() !== 'GET') {
            return;
        }

        if ($response->getStatusCode() !== 200) {
            return;
        }

        $contentType = strtolower((string) $response->headers->get('Content-Type', ''));
        if ($contentType !== '' && ! str_contains($contentType, 'text/html') && ! str_contains($contentType, 'application/xhtml+xml')) {
            return;
        }

        if (! $this->shouldLogPath($request)) {
            return;
        }

        if ($request->expectsJson()) {
            return;
        }

        try {
            $ua = $request->userAgent();
            $isBot = $this->isProbablyBot($ua);

            $pathStored = $this->truncate($request->path(), 500) ?? '';

            $ttlMinutes = max(1, (int) config('site_traffic.dedupe_ttl_minutes', 30));
            $dedupeKey = $this->dedupeCacheKey($request, $pathStored, (string) $ua);

            // Atomic: only the first hit in the TTL window reserves this path for this visitor.
            if (! Cache::add($dedupeKey, 1, now()->addMinutes($ttlMinutes))) {
                return;
            }

            try {
                SiteVisit::query()->create([
                    'path' => $pathStored,
                    'query_string' => $this->truncate($request->getQueryString(), 4000),
                    'route_name' => $this->truncate($request->route()?->getName(), 191),
                    'ip_address' => (string) $request->ip(),
                    'user_agent' => $this->truncate($ua, 8000),
                    'referer' => $this->truncate($request->headers->get('referer'), 2000),
                    'session_id' => $this->truncate($request->hasSession() ? $request->session()->getId() : null, 191),
                    'is_bot' => $isBot,
                ]);
            } catch (\Throwable) {
                Cache::forget($dedupeKey);
            }
        } catch (\Throwable) {
            // Never break user-facing responses for analytics logging.
        }
    }

    /**
     * One counted visit per visitor + path within the configured TTL (refreshes deduped).
     */
    private function dedupeCacheKey(Request $request, string $pathStored, string $userAgent): string
    {
        $sessionId = '';
        if ($request->hasSession()) {
            try {
                $sessionId = (string) $request->session()->getId();
            } catch (\Throwable) {
                $sessionId = '';
            }
        }

        $visitorKey = $sessionId !== ''
            ? $sessionId
            : hash('sha256', (string) $request->ip() . '|' . substr($userAgent, 0, 160));

        $pathKey = strtolower($pathStored);

        return 'site_traffic:dedupe:' . hash('sha256', $visitorKey . "\0" . $pathKey);
    }

    private function shouldLogPath(Request $request): bool
    {
        $path = strtolower(trim($request->path(), '/'));

        $blockedPrefixes = [
            'admin',
            'dashboard',
            'webhooks',
            'build',
            'storage',
            'livewire',
            'sanctum',
            'vendor',
            '_ignition',
            'telescope',
            'horizon',
        ];

        foreach ($blockedPrefixes as $prefix) {
            if ($path === $prefix || str_starts_with($path, $prefix . '/')) {
                return false;
            }
        }

        $blockedExact = [
            'login',
            'register',
            'forgot-password',
            'robots.txt',
            'sitemap.xml',
            'sitemap-main.xml',
            'sitemap-blog.xml',
            'sitemap-leads.xml',
            'up',
            'ui-test',
        ];

        if (in_array($path, $blockedExact, true)) {
            return false;
        }

        if (str_contains($path, 'reset-password')) {
            return false;
        }

        return true;
    }

    private function isProbablyBot(?string $userAgent): bool
    {
        if ($userAgent === null || $userAgent === '') {
            return false;
        }

        return (bool) preg_match('/bot|crawl|spider|slurp|bingpreview|facebookexternal|embedly|preview|lighthouse|gtmetrix|pingdom|uptimerobot/i', $userAgent);
    }

    private function truncate(?string $value, int $max): ?string
    {
        if ($value === null) {
            return null;
        }

        if (strlen($value) <= $max) {
            return $value;
        }

        return substr($value, 0, $max);
    }
}
