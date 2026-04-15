<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ApiUsageLog;
use Illuminate\Support\Facades\Auth;

class ApiDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $organization = $user->organization;

        $stats = [
            'total_calls' => ApiUsageLog::where('organization_id', $organization->id)->count(),
            'success_rate' => $this->calculateSuccessRate($organization->id),
            'failed_calls' => ApiUsageLog::where('organization_id', $organization->id)->where('status', 'failed')->count(),
            'wallet_balance' => $organization->wallet_balance,
            'recent_logs' => ApiUsageLog::where('organization_id', $organization->id)
                ->latest()
                ->limit(10)
                ->get(),
        ];

        return view('admin.api.overview', compact('stats'));
    }

    protected function calculateSuccessRate($orgId): float
    {
        $total = ApiUsageLog::where('organization_id', $orgId)->count();
        if ($total === 0)
            return 0;

        $success = ApiUsageLog::where('organization_id', $orgId)
            ->where('status', 'success')
            ->count();

        return round(($success / $total) * 100, 2);
    }
}
