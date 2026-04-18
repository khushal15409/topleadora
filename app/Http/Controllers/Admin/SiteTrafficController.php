<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteVisit;
use App\Support\Roles;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class SiteTrafficController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless($request->user()?->hasRole(Roles::SUPER_ADMIN), 403);

        $from = $this->parseDate($request->query('from'), now()->subDays(30)->toDateString());
        $to = $this->parseDate($request->query('to'), now()->toDateString());
        if (Carbon::parse($from)->gt(Carbon::parse($to))) {
            [$from, $to] = [$to, $from];
        }

        $pathFilter = trim((string) $request->query('path', ''));
        $bots = $request->query('bots', 'exclude'); // exclude | only | all

        $base = SiteVisit::query()
            ->whereBetween('created_at', [
                Carbon::parse($from)->startOfDay(),
                Carbon::parse($to)->endOfDay(),
            ]);

        if ($pathFilter !== '') {
            $base->where('path', 'like', '%' . str_replace(['%', '_'], ['\\%', '\\_'], $pathFilter) . '%');
        }

        if ($bots === 'exclude') {
            $base->where('is_bot', false);
        } elseif ($bots === 'only') {
            $base->where('is_bot', true);
        }

        $statsBase = clone $base;

        $totalViews = (clone $statsBase)->count();
        $uniqueIps = (int) ((clone $statsBase)->selectRaw('COUNT(DISTINCT ip_address) as aggregate')->value('aggregate') ?? 0);
        $uniqueSessions = (int) ((clone $statsBase)
            ->whereNotNull('session_id')
            ->selectRaw('COUNT(DISTINCT session_id) as aggregate')
            ->value('aggregate') ?? 0);

        $topPaths = (clone $statsBase)
            ->selectRaw('path, COUNT(*) as visits')
            ->groupBy('path')
            ->orderByDesc('visits')
            ->limit(12)
            ->get();

        $visits = (clone $base)
            ->orderByDesc('id')
            ->paginate(40)
            ->withQueryString();

        return view('admin.site-traffic.index', compact(
            'from',
            'to',
            'pathFilter',
            'bots',
            'totalViews',
            'uniqueIps',
            'uniqueSessions',
            'topPaths',
            'visits'
        ));
    }

    private function parseDate(?string $value, string $fallback): string
    {
        if ($value === null || $value === '') {
            return $fallback;
        }

        try {
            return Carbon::parse($value)->toDateString();
        } catch (\Throwable) {
            return $fallback;
        }
    }
}
