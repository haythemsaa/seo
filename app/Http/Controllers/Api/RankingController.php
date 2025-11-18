<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class RankingController extends Model
{
    /**
     * Get rankings for a project.
     */
    public function index(Request $request, Project $project): JsonResponse
    {
        $this->authorize('view', $project);

        $validated = $request->validate([
            'keyword_id' => 'sometimes|exists:keywords,id',
            'device_type' => 'sometimes|in:desktop,mobile,tablet',
            'date_from' => 'sometimes|date',
            'date_to' => 'sometimes|date',
        ]);

        $query = $project->keywords()->with(['rankings' => function ($query) use ($validated) {
            if (isset($validated['device_type'])) {
                $query->where('device_type', $validated['device_type']);
            }
            if (isset($validated['date_from'])) {
                $query->where('checked_at', '>=', $validated['date_from']);
            }
            if (isset($validated['date_to'])) {
                $query->where('checked_at', '<=', $validated['date_to']);
            }
            $query->orderBy('checked_at', 'desc');
        }]);

        if (isset($validated['keyword_id'])) {
            $query->where('id', $validated['keyword_id']);
        }

        $keywords = $query->get();

        return response()->json([
            'data' => $keywords,
        ]);
    }
}
