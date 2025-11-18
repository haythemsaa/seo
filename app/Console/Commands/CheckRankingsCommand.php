<?php

namespace App\Console\Commands;

use App\Jobs\CheckKeywordRankingsJob;
use App\Models\Project;
use Illuminate\Console\Command;

class CheckRankingsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seo:check-rankings {--project= : Specific project ID}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check keyword rankings for all projects or a specific project';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $projectId = $this->option('project');

        if ($projectId) {
            $project = Project::findOrFail($projectId);
            $this->info("Dispatching ranking check for project: {$project->name}");
            dispatch(new CheckKeywordRankingsJob($project));
        } else {
            $projects = Project::where('is_active', true)->get();
            $this->info("Dispatching ranking checks for {$projects->count()} projects");

            foreach ($projects as $project) {
                dispatch(new CheckKeywordRankingsJob($project))->delay(now()->addSeconds($projects->search($project) * 10));
            }
        }

        $this->info('Ranking check jobs dispatched successfully');

        return Command::SUCCESS;
    }
}
