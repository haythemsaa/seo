<?php

namespace App\Console\Commands;

use App\Models\Project;
use App\Models\Report;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class GenerateReports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seo:generate-reports
                            {--type=monthly : Report type (daily, weekly, monthly)}
                            {--project= : Specific project ID}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate SEO reports for projects';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $type = $this->option('type');
        $this->info("Generating {$type} reports...");

        $query = Project::where('status', 'active');

        if ($projectId = $this->option('project')) {
            $query->where('id', $projectId);
        }

        $projects = $query->get();

        if ($projects->isEmpty()) {
            $this->warn('No projects found.');
            return self::FAILURE;
        }

        $bar = $this->output->createProgressBar($projects->count());
        $bar->start();

        $generated = 0;
        $failed = 0;

        foreach ($projects as $project) {
            try {
                // Generate report
                $report = Report::create([
                    'project_id' => $project->id,
                    'type' => $type,
                    'period_start' => $this->getPeriodStart($type),
                    'period_end' => now(),
                    'status' => 'generating',
                ]);

                // TODO: Queue actual report generation job
                // GenerateReportJob::dispatch($report);

                $generated++;
            } catch (\Exception $e) {
                $failed++;
                Log::error('Failed to generate report', [
                    'project_id' => $project->id,
                    'error' => $e->getMessage(),
                ]);
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->info("✓ Queued {$generated} report(s).");

        if ($failed > 0) {
            $this->error("✗ Failed to queue {$failed} report(s). Check logs.");
        }

        return self::SUCCESS;
    }

    /**
     * Get period start date based on report type.
     */
    private function getPeriodStart(string $type): \Carbon\Carbon
    {
        return match($type) {
            'daily' => now()->subDay(),
            'weekly' => now()->subWeek(),
            'monthly' => now()->subMonth(),
            default => now()->subMonth(),
        };
    }
}
