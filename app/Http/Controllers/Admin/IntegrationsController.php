<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateIntegrationsRequest;
use App\Services\IntegrationSettingsService;
use App\Support\Roles;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\View\View;

class IntegrationsController extends Controller
{
    public function index(Request $request, IntegrationSettingsService $settings): View
    {
        abort_unless($request->user()?->hasRole(Roles::SUPER_ADMIN), 403);

        $data = $settings->all();

        return view('admin.integrations.index', $data);
    }

    public function update(UpdateIntegrationsRequest $request, IntegrationSettingsService $settings): RedirectResponse
    {
        abort_unless($request->user()?->hasRole(Roles::SUPER_ADMIN), 403);

        $settings->save($request->validated());

        // Ensure changes reflect immediately across cached views/config.
        Artisan::call('cache:clear');
        Artisan::call('view:clear');

        return redirect()
            ->route('admin.integrations.index')
            ->with('success', __('Integrations saved.'));
    }
}
