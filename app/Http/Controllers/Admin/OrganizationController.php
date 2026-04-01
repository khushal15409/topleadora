<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OrganizationStoreRequest;
use App\Http\Requests\Admin\OrganizationUpdateRequest;
use App\Models\Organization;
use App\Models\Plan;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class OrganizationController extends Controller
{
    public function index(): View
    {
        $organizations = Organization::query()
            ->with(['plan'])
            ->withCount('users')
            ->orderBy('name')
            ->get();

        return view('admin.organizations.index', compact('organizations'));
    }

    public function create(): View
    {
        $plans = Plan::query()->where('is_active', true)->orderBy('sort_order')->orderBy('name')->get();

        return view('admin.organizations.create', ['plans' => $plans, 'organization' => null]);
    }

    public function store(OrganizationStoreRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $slug = $data['slug'] !== null && $data['slug'] !== ''
            ? $data['slug']
            : Organization::uniqueSlugFromName($data['name']);

        $payload = [
            'name' => $data['name'],
            'slug' => $slug,
            'status' => $data['status'],
            'mobile_number' => $data['mobile_number'] ?? null,
            'onboarding_completed' => $request->boolean('onboarding_completed'),
        ];

        if (! empty($data['plan_id'])) {
            $payload['plan_id'] = (int) $data['plan_id'];
            $payload['is_trial'] = false;
            $payload['trial_ends_at'] = null;
        } else {
            $payload['plan_id'] = null;
            $payload['is_trial'] = $request->boolean('is_trial', true);
            $payload['trial_ends_at'] = isset($data['trial_ends_at']) && $data['trial_ends_at'] !== ''
                ? $data['trial_ends_at']
                : now()->addDays(7);
        }

        Organization::query()->create($payload);

        return redirect()
            ->route('admin.organizations.index')
            ->with('success', __('Organization created.'));
    }

    public function edit(Organization $organization): View
    {
        $plans = Plan::query()->where('is_active', true)->orderBy('sort_order')->orderBy('name')->get();

        return view('admin.organizations.edit', compact('organization', 'plans'));
    }

    public function update(OrganizationUpdateRequest $request, Organization $organization): RedirectResponse
    {
        $data = $request->validated();
        $slug = $data['slug'] !== null && $data['slug'] !== ''
            ? $data['slug']
            : Organization::uniqueSlugFromName($data['name']);

        $payload = [
            'name' => $data['name'],
            'slug' => $slug,
            'status' => $data['status'],
            'mobile_number' => $data['mobile_number'] ?? null,
            'onboarding_completed' => $request->boolean('onboarding_completed'),
        ];

        if (! empty($data['plan_id'])) {
            $payload['plan_id'] = (int) $data['plan_id'];
            $payload['is_trial'] = false;
            $payload['trial_ends_at'] = null;
        } else {
            $payload['plan_id'] = null;
            $payload['is_trial'] = $request->boolean('is_trial', true);
            if (isset($data['trial_ends_at']) && $data['trial_ends_at'] !== '') {
                $payload['trial_ends_at'] = $data['trial_ends_at'];
            } else {
                $payload['trial_ends_at'] = $organization->trial_ends_at ?? now()->addDays(7);
            }
        }

        $organization->update($payload);

        return redirect()
            ->route('admin.organizations.index')
            ->with('success', __('Organization updated.'));
    }

    public function destroy(Organization $organization): RedirectResponse
    {
        if ($organization->users()->exists()) {
            return redirect()
                ->route('admin.organizations.index')
                ->withErrors(['delete' => __('Detach or reassign users before deleting this organization.')]);
        }

        $organization->delete();

        return redirect()
            ->route('admin.organizations.index')
            ->with('success', __('Organization removed.'));
    }
}
