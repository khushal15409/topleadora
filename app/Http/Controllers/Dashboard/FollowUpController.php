<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FollowUpController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $this->authorize('viewAny', Lead::class);

        $tab = $request->string('tab')->toString() ?: 'today';
        if (! in_array($tab, ['today', 'upcoming', 'completed'], true)) {
            $tab = 'today';
        }

        $rows = match ($tab) {
            'today' => Lead::query()
                ->visibleTo($user)
                ->with(['assignee:id,name'])
                ->whereNull('followup_completed_at')
                ->whereDate('next_followup_at', now()->toDateString())
                ->orderBy('next_followup_at')
                ->get(),
            'upcoming' => Lead::query()
                ->visibleTo($user)
                ->with(['assignee:id,name'])
                ->whereNull('followup_completed_at')
                ->whereNotNull('next_followup_at')
                ->where('next_followup_at', '>', now()->endOfDay())
                ->orderBy('next_followup_at')
                ->get(),
            'completed' => Lead::query()
                ->visibleTo($user)
                ->with(['assignee:id,name'])
                ->whereNotNull('followup_completed_at')
                ->orderByDesc('followup_completed_at')
                ->get(),
        };

        return view('dashboard.followups.index', compact('rows', 'tab'));
    }

    public function complete(Request $request, Lead $lead): RedirectResponse
    {
        $user = $request->user();
        $lead = Lead::query()->visibleTo($user)->whereKey($lead->getKey())->firstOrFail();
        $this->authorize('update', $lead);

        $lead->update([
            'followup_completed_at' => now(),
            'next_followup_at' => null,
        ]);

        return redirect()
            ->route('dashboard.followups.index', ['tab' => 'today'])
            ->with('success', __('Follow-up marked complete.'));
    }
}
