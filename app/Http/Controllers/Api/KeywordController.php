<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Keyword;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class KeywordController extends Controller
{
    /**
     * Display a listing of keywords for a project.
     */
    public function index(Request $request, Project $project): JsonResponse
    {
        $this->authorize('view', $project);

        $keywords = $project->keywords()
            ->with('latestRanking')
            ->when($request->query('active'), function ($query) {
                return $query->where('is_active', true);
            })
            ->when($request->query('cluster_id'), function ($query, $clusterId) {
                return $query->where('cluster_id', $clusterId);
            })
            ->get();

        $stats = [
            'total' => $keywords->count(),
            'top_3' => 0,
            'top_10' => 0,
            'top_50' => 0,
            'not_ranked' => 0,
        ];

        foreach ($keywords as $keyword) {
            $ranking = $keyword->latestRanking;
            if (!$ranking || !$ranking->position) {
                $stats['not_ranked']++;
            } elseif ($ranking->position <= 3) {
                $stats['top_3']++;
            } elseif ($ranking->position <= 10) {
                $stats['top_10']++;
            } elseif ($ranking->position <= 50) {
                $stats['top_50']++;
            }
        }

        return response()->json([
            'data' => $keywords,
            'meta' => $stats,
        ]);
    }

    /**
     * Store newly created keywords.
     */
    public function store(Request $request, Project $project): JsonResponse
    {
        $this->authorize('update', $project);

        $validated = $request->validate([
            'keywords' => 'required|array',
            'keywords.*.keyword' => 'required|string|max:500',
            'keywords.*.search_volume' => 'nullable|integer|min:0',
            'keywords.*.cpc' => 'nullable|numeric|min:0',
            'keywords.*.competition' => 'nullable|numeric|min:0|max:1',
            'keywords.*.difficulty_score' => 'nullable|integer|min:0|max:100',
            'keywords.*.search_intent' => 'nullable|in:informational,navigational,commercial,transactional',
            'keywords.*.tags' => 'nullable|array',
        ]);

        // Check limits
        $organization = $project->organization;
        $limits = $organization->getSubscriptionLimits();
        $currentCount = $project->keywords()->count();
        $newCount = count($validated['keywords']);

        if ($limits['keywords'] !== -1 && ($currentCount + $newCount) > $limits['keywords']) {
            return response()->json([
                'error' => 'Keyword limit reached for your subscription plan'
            ], 403);
        }

        $createdKeywords = [];
        foreach ($validated['keywords'] as $keywordData) {
            $keyword = $project->keywords()->updateOrCreate(
                [
                    'project_id' => $project->id,
                    'keyword' => $keywordData['keyword'],
                ],
                $keywordData
            );
            $createdKeywords[] = $keyword;
        }

        return response()->json([
            'data' => $createdKeywords,
            'message' => count($createdKeywords) . ' keyword(s) added successfully',
        ], 201);
    }

    /**
     * Display the specified keyword.
     */
    public function show(Request $request, Project $project, Keyword $keyword): JsonResponse
    {
        $this->authorize('view', $project);

        if ($keyword->project_id !== $project->id) {
            return response()->json(['error' => 'Keyword not found'], 404);
        }

        $keyword->load('rankings');

        $positionChange = $keyword->getPositionChange();

        return response()->json([
            'data' => $keyword,
            'meta' => [
                'position_change' => $positionChange,
                'is_in_top_10' => $keyword->isInTopN(10),
                'total_checks' => $keyword->rankings->count(),
            ],
        ]);
    }

    /**
     * Update the specified keyword.
     */
    public function update(Request $request, Project $project, Keyword $keyword): JsonResponse
    {
        $this->authorize('update', $project);

        if ($keyword->project_id !== $project->id) {
            return response()->json(['error' => 'Keyword not found'], 404);
        }

        $validated = $request->validate([
            'search_volume' => 'sometimes|integer|min:0',
            'cpc' => 'sometimes|numeric|min:0',
            'competition' => 'sometimes|numeric|min:0|max:1',
            'difficulty_score' => 'sometimes|integer|min:0|max:100',
            'search_intent' => 'sometimes|in:informational,navigational,commercial,transactional',
            'tags' => 'sometimes|array',
            'is_active' => 'sometimes|boolean',
        ]);

        $keyword->update($validated);

        return response()->json([
            'data' => $keyword,
            'message' => 'Keyword updated successfully',
        ]);
    }

    /**
     * Remove the specified keyword.
     */
    public function destroy(Request $request, Project $project, Keyword $keyword): JsonResponse
    {
        $this->authorize('delete', $project);

        if ($keyword->project_id !== $project->id) {
            return response()->json(['error' => 'Keyword not found'], 404);
        }

        $keyword->delete();

        return response()->json([
            'message' => 'Keyword deleted successfully',
        ], 204);
    }
}
