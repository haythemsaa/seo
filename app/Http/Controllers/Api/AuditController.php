<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\CrawlSession;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AuditController extends Controller
{
    /**
     * Get audits for a project.
     */
    public function index(Request $request, Project $project): JsonResponse
    {
        $this->authorize('view', $project);

        $audits = $project->crawlSessions()
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'data' => $audits,
        ]);
    }

    /**
     * Get a specific audit.
     */
    public function show(Request $request, CrawlSession $audit): JsonResponse
    {
        $this->authorize('view', $audit->project);

        $audit->load('pages');

        $seoScore = $audit->calculateSeoScore();

        return response()->json([
            'data' => $audit,
            'meta' => [
                'seo_score' => $seoScore,
                'progress' => $audit->getProgressPercentage(),
            ],
        ]);
    }

    /**
     * Create a new audit.
     */
    public function create(Request $request, Project $project): JsonResponse
    {
        $this->authorize('update', $project);

        $validated = $request->validate([
            'crawl_type' => 'sometimes|in:full,incremental,targeted',
            'max_pages' => 'sometimes|integer|min:1',
        ]);

        $crawlSession = $project->crawlSessions()->create([
            'crawl_type' => $validated['crawl_type'] ?? 'full',
            'status' => 'pending',
            'pages_total' => $validated['max_pages'] ?? 10000,
        ]);

        // TODO: Dispatch crawl job
        // dispatch(new CrawlWebsiteJob($crawlSession));

        return response()->json([
            'data' => $crawlSession,
            'message' => 'Audit created and queued successfully',
        ], 201);
    }
}
