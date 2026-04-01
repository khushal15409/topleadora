<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\StoreBroadcastRequest;
use App\Models\Broadcast;
use App\Models\Lead;
use App\Services\BroadcastDeliveryService;
use App\Services\WhatsAppCloudApiService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BroadcastController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $this->authorize('viewAny', Broadcast::class);

        $leads = Lead::query()
            ->visibleTo($user)
            ->whereNotNull('phone')
            ->orderBy('name')
            ->get(['id', 'name', 'phone']);

        $history = Broadcast::query()
            ->forOrganization((int) $user->organization_id)
            ->when($request->filled('q'), function ($q) use ($request): void {
                $term = '%'.$request->string('q')->trim().'%';
                $q->where('message', 'like', $term);
            })
            ->orderByDesc('id')
            ->paginate(12)
            ->withQueryString();

        return view('dashboard.broadcast.index', compact('leads', 'history'));
    }

    public function store(StoreBroadcastRequest $request, BroadcastDeliveryService $delivery): RedirectResponse
    {
        $user = $request->user();

        $leadsQuery = Lead::query()->visibleTo($user)->whereNotNull('phone');

        if ($request->boolean('send_to_all')) {
            $recipients = $leadsQuery->get();
            $leadIds = $recipients->pluck('id')->all();
        } else {
            $ids = array_map('intval', $request->input('lead_ids', []));
            $recipients = $leadsQuery->whereIn('id', $ids)->get();
            $leadIds = $recipients->pluck('id')->all();

            if ($recipients->count() !== count(array_unique($ids))) {
                return redirect()
                    ->route('dashboard.broadcast.index')
                    ->withErrors(['lead_ids' => __('One or more leads are invalid or missing a phone number.')]);
            }
        }

        if ($recipients->isEmpty()) {
            return redirect()
                ->route('dashboard.broadcast.index')
                ->withErrors(['message' => __('No recipients with a phone number.')]);
        }

        $wa = app(WhatsAppCloudApiService::class);
        $result = $delivery->sendBulk($recipients, $request->string('message')->toString(), $wa);
        $sent = (int) ($result['sent'] ?? 0);
        $failed = (int) ($result['failed'] ?? 0);
        $lastError = $result['last_error'] ?? null;

        Broadcast::query()->create([
            'organization_id' => $user->organization_id,
            'user_id' => $user->id,
            'message' => $request->string('message')->toString(),
            'send_to_all' => $request->boolean('send_to_all'),
            'lead_ids' => $leadIds,
            'total_recipients' => $recipients->count(),
            'sent_count' => $sent,
            'failed_count' => $failed,
            'last_error' => is_string($lastError) ? $lastError : null,
            'status' => $failed > 0 && $sent > 0 ? 'partial' : ($failed > 0 ? 'failed' : 'completed'),
        ]);

        return redirect()
            ->route('dashboard.broadcast.index')
            ->with('success', __('Broadcast sent: :sent sent, :failed failed.', ['sent' => $sent, 'failed' => $failed]));
    }
}
