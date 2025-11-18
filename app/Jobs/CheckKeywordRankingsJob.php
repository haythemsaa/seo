<?php

namespace App\Jobs;

use App\Models\Project;
use App\Services\SEO\RankTrackerService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CheckKeywordRankingsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 1800; // 30 minutes
    protected Project $project;

    /**
     * Create a new job instance.
     */
    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info("Checking rankings for project {$this->project->id}");

        try {
            $tracker = new RankTrackerService();
            $count = $tracker->checkProjectRankings($this->project);

            Log::info("Checked {$count} keyword rankings for project {$this->project->id}");

        } catch (\Exception $e) {
            Log::error("Failed to check rankings for project {$this->project->id}: " . $e->getMessage());
        }
    }
}
