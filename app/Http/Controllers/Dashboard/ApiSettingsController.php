<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApiSettingsController extends Controller
{
    public function index(Request $request)
    {
        $organization = $request->user()->organization;
        return view('admin.api.settings', compact('organization'));
    }

    public function update(Request $request)
    {
        $organization = $request->user()->organization;

        $validated = $request->validate([
            'webhook_url' => 'nullable|url|max:255',
            'allowed_ips' => 'nullable|string',
        ]);

        $ips = [];
        if (!empty($validated['allowed_ips'])) {
            $ips = array_map('trim', explode(',', $validated['allowed_ips']));
        }

        $organization->update([
            'webhook_url' => $validated['webhook_url'] ?? null,
            'allowed_ips' => empty($ips) ? null : json_encode($ips),
        ]);

        return back()->with('success', 'API Settings updated successfully.');
    }
}
