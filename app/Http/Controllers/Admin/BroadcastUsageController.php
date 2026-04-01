<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Broadcast;
use App\Models\Organization;
use App\Support\Roles;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BroadcastUsageController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless($request->user()?->hasRole(Roles::SUPER_ADMIN), 403);

        $from = $request->date('from');
        $to = $request->date('to');
        if ($to) {
            $to = $to->endOfDay();
        }

        $orgs = Organization::query()
            ->select(['organizations.id', 'organizations.name'])
            ->when($request->filled('q'), function (Builder $q) use ($request): void {
                $term = '%'.$request->string('q')->trim().'%';
                $q->where('organizations.name', 'like', $term);
            })
            ->withCount([
                'broadcasts as total_broadcasts_sent' => function (Builder $q) use ($from, $to): void {
                    $this->range($q, $from, $to);
                },
            ])
            ->withSum([
                'broadcasts as total_messages_sent' => function (Builder $q) use ($from, $to): void {
                    $this->range($q, $from, $to);
                },
            ], 'sent_count')
            ->withMax([
                'broadcasts as last_broadcast_at' => function (Builder $q) use ($from, $to): void {
                    $this->range($q, $from, $to);
                },
            ], 'created_at')
            ->orderBy('organizations.name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.broadcast-usage.index', compact('orgs', 'from', 'to'));
    }

    public function show(Request $request, Organization $organization): View
    {
        abort_unless($request->user()?->hasRole(Roles::SUPER_ADMIN), 403);

        $from = $request->date('from');
        $to = $request->date('to');
        if ($to) {
            $to = $to->endOfDay();
        }

        $broadcasts = Broadcast::query()
            ->forOrganization($organization->id)
            ->when($from, fn (Builder $q) => $q->where('created_at', '>=', $from))
            ->when($to, fn (Builder $q) => $q->where('created_at', '<=', $to))
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.broadcast-usage.show', compact('organization', 'broadcasts', 'from', 'to'));
    }

    private function range(Builder $q, $from, $to): void
    {
        if ($from) {
            $q->where('created_at', '>=', $from);
        }
        if ($to) {
            $q->where('created_at', '<=', $to);
        }
    }
}

