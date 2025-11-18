<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProjectController extends Controller
{
    /**
     * Display a listing of projects.
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $organization = $user->organization;

        if (!$organization) {
            return response()->json(['error' => 'No organization found'], 404);
        }

        $projects = $organization->projects()
            ->with(['keywords', 'latestCrawl'])
            ->get();

        return response()->json([
            'data' => $projects,
            'meta' => [
                'total' => $projects->count(),
                'limit' => $organization->getSubscriptionLimits()['projects'],
            ],
        ]);
    }

    /**
     * Store a newly created project.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'website_url' => 'required|url|max:500',
            'country_code' => 'nullable|string|size:2',
            'language_code' => 'nullable|string|max:5',
            'search_engines' => 'nullable|array',
            'competitors' => 'nullable|array',
        ]);

        $user = $request->user();
        $organization = $user->organization;

        // Check limits
        $limits = $organization->getSubscriptionLimits();
        if ($limits['projects'] !== -1 && $organization->projects()->count() >= $limits['projects']) {
            return response()->json([
                'error' => 'Project limit reached for your subscription plan'
            ], 403);
        }

        // Extract main domain from URL
        $parsedUrl = parse_url($validated['website_url']);
        $mainDomain = $parsedUrl['host'] ?? '';

        $project = $organization->projects()->create([
            ...$validated,
            'main_domain' => $mainDomain,
            'country_code' => $validated['country_code'] ?? 'FR',
            'language_code' => $validated['language_code'] ?? 'fr-FR',
        ]);

        return response()->json([
            'data' => $project,
            'message' => 'Project created successfully',
        ], 201);
    }

    /**
     * Display the specified project.
     */
    public function show(Request $request, Project $project): JsonResponse
    {
        $this->authorize('view', $project);

        $project->load([
            'keywords',
            'activeKeywords',
            'latestCrawl',
            'backlinks',
            'pendingRecommendations',
        ]);

        // Calculate visibility score
        $visibilityScore = $project->calculateVisibilityScore();

        return response()->json([
            'data' => $project,
            'meta' => [
                'visibility_score' => $visibilityScore,
                'total_keywords' => $project->keywords->count(),
                'active_keywords' => $project->activeKeywords->count(),
                'total_backlinks' => $project->backlinks->count(),
                'pending_recommendations' => $project->pendingRecommendations->count(),
            ],
        ]);
    }

    /**
     * Update the specified project.
     */
    public function update(Request $request, Project $project): JsonResponse
    {
        $this->authorize('update', $project);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'website_url' => 'sometimes|required|url|max:500',
            'country_code' => 'nullable|string|size:2',
            'language_code' => 'nullable|string|max:5',
            'search_engines' => 'nullable|array',
            'competitors' => 'nullable|array',
            'is_active' => 'sometimes|boolean',
            'crawl_frequency' => 'sometimes|in:daily,weekly,monthly',
        ]);

        $project->update($validated);

        return response()->json([
            'data' => $project,
            'message' => 'Project updated successfully',
        ]);
    }

    /**
     * Remove the specified project.
     */
    public function destroy(Request $request, Project $project): JsonResponse
    {
        $this->authorize('delete', $project);

        $project->delete();

        return response()->json([
            'message' => 'Project deleted successfully',
        ], 204);
    }
}
