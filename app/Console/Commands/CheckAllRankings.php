<?php

namespace App\Console\Commands;

use App\Jobs\CheckKeywordRankings;
use App\Models\Project;
use Illuminate\Console\Command;

class CheckAllRankings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seo:check-rankings
                            {--project= : Specific project ID to check}
                            {--active-only : Only check active projects}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check keyword rankings for all or specific projects';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Starting ranking checks...');

        $query = Project::query();

        if ($projectId = $this->option('project')) {
            $query->where('id', $projectId);
        }

        if ($this->option('active-only')) {
            $query->where('status', 'active');
        }

        $projects = $query->with('keywords')->get();

        if ($projects->isEmpty()) {
            $this->warn('No projects found to check.');
            return self::FAILURE;
        }

        $bar = $this->output->createProgressBar($projects->count());
        $bar->start();

        $queued = 0;

        foreach ($projects as $project) {
            if ($project->keywords()->count() > 0) {
                CheckKeywordRankings::dispatch($project);
                $queued++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->info("✓ Queued ranking checks for {$queued} project(s).");

        return self::SUCCESS;
    }
}
