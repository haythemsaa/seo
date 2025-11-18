<?php

namespace App\Jobs;

use App\Models\Project;
use App\Models\Keyword;
use App\Models\KeywordRanking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CheckKeywordRankings implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 600; // 10 minutes

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Project $project,
        public ?array $keywordIds = null
    ) {
        $this->onQueue('rankings');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('Starting ranking check', [
            'project_id' => $this->project->id,
        ]);

        // Get keywords to check
        $query = $this->project->keywords();

        if ($this->keywordIds) {
            $query->whereIn('id', $this->keywordIds);
        }

        $keywords = $query->get();

        $checked = 0;
        $updated = 0;

        foreach ($keywords as $keyword) {
            try {
                $position = $this->checkRanking($keyword);

                // Store ranking history
                KeywordRanking::create([
                    'keyword_id' => $keyword->id,
                    'position' => $position,
                    'url' => $keyword->url,
                    'device' => $this->project->device,
                    'location' => $this->project->country,
                    'checked_at' => now(),
                ]);

                // Update keyword
                $keyword->update([
                    'previous_position' => $keyword->current_position,
                    'current_position' => $position,
                    'best_position' => min($position, $keyword->best_position ?? PHP_INT_MAX),
                    'worst_position' => max($position, $keyword->worst_position ?? 0),
                    'last_checked_at' => now(),
                ]);

                $checked++;

                if ($keyword->previous_position !== $position) {
                    $updated++;

                    // Send notification if significant change
                    if (abs($keyword->previous_position - $position) >= 5) {
                        // dispatch(new SendRankingChangeNotification($keyword));
                    }
                }

                // Rate limiting - avoid being blocked by search engines
                sleep(rand(2, 5));
            } catch (\Exception $e) {
                Log::error('Failed to check ranking', [
                    'keyword_id' => $keyword->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        Log::info('Ranking check completed', [
            'project_id' => $this->project->id,
            'checked' => $checked,
            'updated' => $updated,
        ]);
    }

    /**
     * Check the ranking for a keyword.
     */
    private function checkRanking(Keyword $keyword): int
    {
        // TODO: Implement actual ranking check
        // This would typically use:
        // - Google Search API
        // - SERPApi
        // - Custom scraping (with caution)

        // Placeholder: simulate ranking check
        $currentPosition = $keyword->current_position ?? rand(1, 100);

        // Simulate small changes
        $change = rand(-5, 5);
        $newPosition = max(1, min(100, $currentPosition + $change));

        return $newPosition;
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Ranking check job failed permanently', [
            'project_id' => $this->project->id,
            'error' => $exception->getMessage(),
        ]);
    }
}
