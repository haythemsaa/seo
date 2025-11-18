<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Daily ranking checks
        $schedule->command('seo:check-rankings')
            ->daily()
            ->at('03:00')
            ->withoutOverlapping();

        // Weekly crawls for weekly projects
        $schedule->call(function () {
            \App\Models\Project::where('is_active', true)
                ->where('crawl_frequency', 'weekly')
                ->chunk(10, function ($projects) {
                    foreach ($projects as $project) {
                        \App\Jobs\CrawlWebsiteJob::dispatch(
                            \App\Models\CrawlSession::create([
                                'project_id' => $project->id,
                                'crawl_type' => 'full',
                                'status' => 'pending',
                            ])
                        );
                    }
                });
        })->weekly()->mondays()->at('02:00');

        // Daily crawls for daily projects
        $schedule->call(function () {
            \App\Models\Project::where('is_active', true)
                ->where('crawl_frequency', 'daily')
                ->chunk(10, function ($projects) {
                    foreach ($projects as $project) {
                        \App\Jobs\CrawlWebsiteJob::dispatch(
                            \App\Models\CrawlSession::create([
                                'project_id' => $project->id,
                                'crawl_type' => 'incremental',
                                'status' => 'pending',
                            ])
                        );
                    }
                });
        })->daily()->at('04:00');

        // Clean old ranking data (keep 90 days)
        $schedule->call(function () {
            \App\Models\KeywordRanking::where('checked_at', '<', now()->subDays(90))->delete();
        })->weekly();

        // Clean old crawled pages (keep 30 days)
        $schedule->call(function () {
            \App\Models\CrawledPage::where('crawled_at', '<', now()->subDays(30))->delete();
        })->weekly();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
