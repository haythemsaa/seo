<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AnalyticsController extends Controller
{
    /**
     * Get analytics data for a project.
     */
    public function index(Request $request, Project $project): JsonResponse
    {
        $this->authorize('view', $project);

        $validated = $request->validate([
            'date_from' => 'sometimes|date',
            'date_to' => 'sometimes|date',
        ]);

        $dateFrom = $validated['date_from'] ?? now()->subDays(30);
        $dateTo = $validated['date_to'] ?? now();

        // Get Search Console metrics if connected
        $gscMetrics = $project->google_search_console_property
            ? \DB::table('search_console_metrics')
                ->where('project_id', $project->id)
                ->whereBetween('date', [$dateFrom, $dateTo])
                ->select([
                    \DB::raw('SUM(clicks) as total_clicks'),
                    \DB::raw('SUM(impressions) as total_impressions'),
                    \DB::raw('AVG(ctr) as avg_ctr'),
                    \DB::raw('AVG(position) as avg_position'),
                ])
                ->first()
            : null;

        // Get ranking distribution
        $rankingDistribution = $project->keywords()
            ->with('latestRanking')
            ->get()
            ->groupBy(function ($keyword) {
                $ranking = $keyword->latestRanking;
                if (!$ranking || !$ranking->position) {
                    return 'not_ranked';
                }
                if ($ranking->position <= 3) return 'top_3';
                if ($ranking->position <= 10) return 'top_10';
                if ($ranking->position <= 50) return 'top_50';
                return 'below_50';
            })
            ->map->count();

        return response()->json([
            'data' => [
                'google_search_console' => $gscMetrics,
                'ranking_distribution' => $rankingDistribution,
                'visibility_score' => $project->calculateVisibilityScore(),
                'total_keywords' => $project->keywords()->count(),
                'total_backlinks' => $project->backlinks()->count(),
                'active_backlinks' => $project->activeBacklinks()->count(),
            ],
            'meta' => [
                'period' => [
                    'from' => $dateFrom,
                    'to' => $dateTo,
                ],
            ],
        ]);
    }
}
