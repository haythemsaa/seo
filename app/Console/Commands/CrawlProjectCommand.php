<?php

namespace App\Console\Commands;

use App\Jobs\CrawlWebsiteJob;
use App\Models\CrawlSession;
use App\Models\Project;
use Illuminate\Console\Command;

class CrawlProjectCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seo:crawl {project : Project ID} {--type=full : Crawl type (full|incremental|targeted)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crawl a project website';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $projectId = $this->argument('project');
        $type = $this->option('type');

        $project = Project::findOrFail($projectId);

        $this->info("Creating crawl session for project: {$project->name}");

        $crawlSession = CrawlSession::create([
            'project_id' => $project->id,
            'crawl_type' => $type,
            'status' => 'pending',
            'pages_total' => 10000,
        ]);

        $this->info("Dispatching crawl job for session: {$crawlSession->id}");

        dispatch(new CrawlWebsiteJob($crawlSession));

        $this->info('Crawl job dispatched successfully');

        return Command::SUCCESS;
    }
}
