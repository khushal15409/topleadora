<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\UpdateLeadStageRequest;
use App\Models\Lead;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PipelineController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $this->authorize('viewAny', Lead::class);

        $leads = Lead::query()
            ->visibleTo($user)
            ->orderBy('name')
            ->get(['id', 'name', 'phone', 'status', 'notes']);

        $columns = [];
        foreach (Lead::pipelineStages() as $stage) {
            $columns[] = [
                'key' => $stage,
                'label' => Lead::statusOptions()[$stage],
                'leads' => $leads->where('status', $stage)->values(),
            ];
        }

        return view('dashboard.pipeline.index', compact('columns'));
    }

    public function updateStage(UpdateLeadStageRequest $request, Lead $lead): JsonResponse
    {
        $user = $request->user();
        $lead = Lead::query()->visibleTo($user)->whereKey($lead->getKey())->firstOrFail();
        $lead->update(['status' => $request->validated('status')]);

        return response()->json(['ok' => true]);
    }
}
