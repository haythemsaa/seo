<?php

namespace App\Observers;

use App\Models\Project;
use Illuminate\Support\Facades\Log;

class ProjectObserver
{
    /**
     * Handle the Project "created" event.
     */
    public function created(Project $project): void
    {
        Log::info('Project created', [
            'project_id' => $project->id,
            'user_id' => $project->user_id,
            'name' => $project->name,
            'url' => $project->url,
        ]);

        // Update user's project count in cache
        cache()->forget("user.{$project->user_id}.projects_count");
    }

    /**
     * Handle the Project "updated" event.
     */
    public function updated(Project $project): void
    {
        if ($project->wasChanged('status')) {
            Log::info('Project status changed', [
                'project_id' => $project->id,
                'old_status' => $project->getOriginal('status'),
                'new_status' => $project->status,
            ]);
        }
    }

    /**
     * Handle the Project "deleted" event.
     */
    public function deleted(Project $project): void
    {
        Log::info('Project deleted', [
            'project_id' => $project->id,
            'name' => $project->name,
        ]);

        // Clear related caches
        cache()->forget("user.{$project->user_id}.projects_count");
        cache()->forget("project.{$project->id}");
    }

    /**
     * Handle the Project "restored" event.
     */
    public function restored(Project $project): void
    {
        Log::info('Project restored', [
            'project_id' => $project->id,
            'name' => $project->name,
        ]);
    }

    /**
     * Handle the Project "force deleted" event.
     */
    public function forceDeleted(Project $project): void
    {
        Log::warning('Project force deleted', [
            'project_id' => $project->id,
            'name' => $project->name,
        ]);
    }
}
