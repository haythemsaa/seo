<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class BacklinkController extends Controller
{
    /**
     * Get backlinks for a project.
     */
    public function index(Request $request, Project $project): JsonResponse
    {
        $this->authorize('view', $project);

        $query = $project->backlinks();

        // Filter by status
        if ($request->query('active_only')) {
            $query->where('is_active', true);
        }

        // Filter by quality
        if ($request->query('min_da')) {
            $query->where('domain_authority', '>=', $request->query('min_da'));
        }

        // Sort
        $sortBy = $request->query('sort_by', 'created_at');
        $sortOrder = $request->query('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $backlinks = $query->paginate(50);

        $stats = [
            'total' => $project->backlinks()->count(),
            'active' => $project->activeBacklinks()->count(),
            'dofollow' => $project->backlinks()->where('link_type', 'dofollow')->count(),
            'avg_da' => $project->backlinks()->avg('domain_authority'),
            'avg_tf' => $project->backlinks()->avg('trust_flow'),
        ];

        return response()->json([
            'data' => $backlinks->items(),
            'meta' => [
                ...$stats,
                'pagination' => [
                    'current_page' => $backlinks->currentPage(),
                    'total_pages' => $backlinks->lastPage(),
                    'per_page' => $backlinks->perPage(),
                    'total' => $backlinks->total(),
                ],
            ],
        ]);
    }
}
