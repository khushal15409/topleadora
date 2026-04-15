<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ApiUsageLog;
use Illuminate\Http\Request;

class ApiUsageLogController extends Controller
{
    public function index(Request $request)
    {
        $organization = $request->user()->organization;
        $query = ApiUsageLog::where('organization_id', $organization->id);

        if ($request->filled('phone')) {
            $query->where('phone', 'like', '%' . $request->phone . '%');
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $logs = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        return view('admin.api.logs', compact('logs'));
    }
}
