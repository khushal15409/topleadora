<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\LeadQuickActionRequest;
use App\Http\Requests\Dashboard\StoreLeadRequest;
use App\Http\Requests\Dashboard\UpdateLeadRequest;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LeadController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $this->authorize('viewAny', Lead::class);

        $leads = Lead::query()
            ->visibleTo($user)
            ->with(['assignee:id,name'])
            ->when($request->filled('q'), function ($q) use ($request): void {
                $term = '%'.$request->string('q')->trim().'%';
                $q->where(function ($q) use ($term): void {
                    $q->where('name', 'like', $term)
                        ->orWhere('phone', 'like', $term);
                });
            })
            ->when(
                $request->filled('status'),
                fn ($q) => $q->where('status', $request->string('status')->toString())
            )
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        $statusFilter = $request->string('status')->toString();
        $statusOptions = Lead::statusOptions();

        $assignableUsers = $user->canViewAllOrganizationLeads()
            ? User::query()
                ->where('organization_id', $user->organization_id)
                ->orderBy('name')
                ->get(['id', 'name'])
            : collect();

        return view('dashboard.leads.index', compact(
            'leads',
            'statusFilter',
            'statusOptions',
            'assignableUsers'
        ));
    }

    public function create(Request $request): View
    {
        $user = $request->user();
        $this->authorize('create', Lead::class);

        $statusOptions = Lead::statusOptions();
        $sourceOptions = Lead::sourceOptions();
        $assignableUsers = $user->canViewAllOrganizationLeads()
            ? User::query()
                ->where('organization_id', $user->organization_id)
                ->orderBy('name')
                ->get(['id', 'name'])
            : collect();
        $lead = new Lead([
            'status' => Lead::STATUS_NEW,
            'source' => Lead::SOURCE_WHATSAPP,
            'assigned_to' => $user->id,
        ]);

        return view('dashboard.leads.create', compact('lead', 'statusOptions', 'sourceOptions', 'assignableUsers'));
    }

    public function store(StoreLeadRequest $request): RedirectResponse
    {
        $user = $request->user();
        $data = $request->validated();
        $data['organization_id'] = $user->organization_id;
        if (! $user->canViewAllOrganizationLeads()) {
            $data['assigned_to'] = $user->id;
        }

        Lead::query()->create($data);

        return redirect()
            ->route('dashboard.leads.index')
            ->with('success', __('Lead added.'));
    }

    public function edit(Request $request, Lead $lead): View
    {
        $user = $request->user();
        $lead = Lead::query()->visibleTo($user)->whereKey($lead->getKey())->firstOrFail();
        $this->authorize('update', $lead);

        $statusOptions = Lead::statusOptions();
        $sourceOptions = Lead::sourceOptions();
        $assignableUsers = $user->canViewAllOrganizationLeads()
            ? User::query()
                ->where('organization_id', $user->organization_id)
                ->orderBy('name')
                ->get(['id', 'name'])
            : collect();

        return view('dashboard.leads.edit', compact('lead', 'statusOptions', 'sourceOptions', 'assignableUsers'));
    }

    public function update(UpdateLeadRequest $request, Lead $lead): RedirectResponse
    {
        $user = $request->user();
        $lead = Lead::query()->visibleTo($user)->whereKey($lead->getKey())->firstOrFail();
        $data = $request->validated();
        if (! $user->canViewAllOrganizationLeads()) {
            unset($data['assigned_to']);
        }
        $lead->update($data);

        return redirect()
            ->route('dashboard.leads.index')
            ->with('success', __('Lead updated.'));
    }

    public function summary(Request $request, Lead $lead): JsonResponse
    {
        $user = $request->user();
        $lead = Lead::query()->visibleTo($user)->whereKey($lead->getKey())->firstOrFail();
        $this->authorize('view', $lead);

        return response()->json([
            'id' => $lead->id,
            'name' => $lead->name,
            'phone' => $lead->phone,
            'status' => $lead->status,
            'status_label' => $lead->statusLabel(),
            'source_label' => $lead->sourceLabel(),
            'notes' => $lead->notes ?? '',
            'next_followup_at' => $lead->next_followup_at?->toIso8601String(),
        ]);
    }

    public function quick(LeadQuickActionRequest $request, Lead $lead): JsonResponse
    {
        $user = $request->user();
        $lead = Lead::query()->visibleTo($user)->whereKey($lead->getKey())->firstOrFail();
        $action = $request->validated('action');

        if ($action === 'status') {
            $lead->update(['status' => $request->validated('status')]);
        } elseif ($action === 'followup') {
            $lead->update([
                'next_followup_at' => $request->validated('next_followup_at'),
                'followup_completed_at' => null,
            ]);
        } elseif ($action === 'note') {
            $line = now()->format('Y-m-d H:i').' — '.$request->validated('note');
            $lead->update(['notes' => trim($line."\n\n".($lead->notes ?? ''))]);
        }

        return response()->json(['ok' => true]);
    }
}
