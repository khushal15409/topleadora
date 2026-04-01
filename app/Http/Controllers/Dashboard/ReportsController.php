<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Services\TenantReportService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportsController extends Controller
{
    public function index(Request $request, TenantReportService $reports): View
    {
        $user = $request->user();
        $this->authorize('viewAny', Lead::class);

        $period = $request->string('period')->toString();
        if (! in_array($period, ['today', 'week', 'month'], true)) {
            $period = 'week';
        }

        $data = $reports->build($user, $period);

        return view('dashboard.reports.index', $data);
    }
}
