<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ReportController extends Controller
{
    /**
     * Get reports for a project.
     */
    public function index(Request $request, Project $project): JsonResponse
    {
        $this->authorize('view', $project);

        $reports = $project->reports()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json([
            'data' => $reports->items(),
            'meta' => [
                'pagination' => [
                    'current_page' => $reports->currentPage(),
                    'total_pages' => $reports->lastPage(),
                    'per_page' => $reports->perPage(),
                    'total' => $reports->total(),
                ],
            ],
        ]);
    }

    /**
     * Generate a new report.
     */
    public function generate(Request $request, Project $project): JsonResponse
    {
        $this->authorize('update', $project);

        $validated = $request->validate([
            'type' => 'required|in:weekly,monthly,custom',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after:period_start',
            'title' => 'nullable|string|max:255',
        ]);

        $report = $project->reports()->create([
            'organization_id' => $project->organization_id,
            'type' => $validated['type'],
            'period_start' => $validated['period_start'],
            'period_end' => $validated['period_end'],
            'title' => $validated['title'] ?? 'Report ' . now()->format('Y-m-d'),
        ]);

        // TODO: Dispatch report generation job
        // dispatch(new GenerateReportJob($report));

        return response()->json([
            'data' => $report,
            'message' => 'Report generation started',
        ], 201);
    }
}
