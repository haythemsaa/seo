<?php

namespace App\Jobs;

use App\Models\Project;
use App\Services\SEO\AiRecommendationEngine;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GenerateRecommendationsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 600;
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
        Log::info("Generating AI recommendations for project {$this->project->id}");

        try {
            $engine = new AiRecommendationEngine();
            $count = $engine->generateRecommendations($this->project);

            Log::info("Generated {$count} recommendations for project {$this->project->id}");

        } catch (\Exception $e) {
            Log::error("Failed to generate recommendations for project {$this->project->id}: " . $e->getMessage());
        }
    }
}
