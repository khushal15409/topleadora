<?php

namespace App\Http\Controllers\Admin\Marketing;

use App\Http\Controllers\Controller;
use App\Models\MarketingLead;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MarketingLeadController extends Controller
{
    public function index(Request $request): View
    {
        $services = Service::query()->orderBy('name')->get(['id', 'name']);

        $leads = MarketingLead::query()
            ->with(['service', 'country', 'landingPage'])
            ->when($request->filled('service_id'), fn ($q) => $q->where('service_id', (int) $request->query('service_id')))
            ->when($request->filled('from'), fn ($q) => $q->whereDate('created_at', '>=', $request->query('from')))
            ->when($request->filled('to'), fn ($q) => $q->whereDate('created_at', '<=', $request->query('to')))
            ->when($request->filled('q'), function ($q) use ($request) {
                $term = '%'.$request->query('q').'%';
                $q->where(function ($q2) use ($term) {
                    $q2->where('name', 'like', $term)
                        ->orWhere('email', 'like', $term)
                        ->orWhere('phone', 'like', $term)
                        ->orWhere('city', 'like', $term);
                });
            })
            ->orderByDesc('id')
            ->paginate(30)
            ->withQueryString();

        return view('admin.marketing.leads.index', compact('leads', 'services'));
    }

    public function export(Request $request): StreamedResponse
    {
        $query = MarketingLead::query()
            ->with(['service', 'country'])
            ->when($request->filled('service_id'), fn ($q) => $q->where('service_id', (int) $request->query('service_id')))
            ->when($request->filled('country_id'), fn ($q) => $q->where('country_id', (int) $request->query('country_id')))
            ->when($request->filled('from'), fn ($q) => $q->whereDate('created_at', '>=', $request->query('from')))
            ->when($request->filled('to'), fn ($q) => $q->whereDate('created_at', '<=', $request->query('to')))
            ->when($request->filled('q'), function ($q) use ($request) {
                $term = '%'.$request->query('q').'%';
                $q->where(function ($q2) use ($term) {
                    $q2->where('name', 'like', $term)
                        ->orWhere('email', 'like', $term)
                        ->orWhere('phone', 'like', $term)
                        ->orWhere('city', 'like', $term);
                });
            })
            ->orderByDesc('id');

        $filename = 'marketing-leads-'.now()->format('Y-m-d-His').'.csv';

        return response()->streamDownload(function () use ($query): void {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['id', 'name', 'email', 'phone', 'city', 'service', 'country_code', 'country_name', 'source_page', 'utm_source', 'utm_medium', 'utm_campaign', 'created_at']);
            $query->chunk(500, function ($rows) use ($out): void {
                foreach ($rows as $row) {
                    fputcsv($out, [
                        $row->id,
                        $row->name,
                        $row->email,
                        $row->phone,
                        $row->city,
                        $row->service?->name,
                        $row->country_code,
                        $row->country_name,
                        $row->source_page,
                        $row->utm_source,
                        $row->utm_medium,
                        $row->utm_campaign,
                        $row->created_at?->toIso8601String(),
                    ]);
                }
            });
            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
