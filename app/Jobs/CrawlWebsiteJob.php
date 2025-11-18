<?php

namespace App\Jobs;

use App\Models\CrawlSession;
use App\Services\Crawler\WebCrawler;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CrawlWebsiteJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 3600; // 1 hour
    public $tries = 1;

    protected CrawlSession $crawlSession;

    /**
     * Create a new job instance.
     */
    public function __construct(CrawlSession $crawlSession)
    {
        $this->crawlSession = $crawlSession;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info("Starting crawl for session {$this->crawlSession->id}");

        try {
            $crawler = new WebCrawler($this->crawlSession);
            $crawler->crawl();

            Log::info("Completed crawl for session {$this->crawlSession->id}");

            // Generate AI recommendations after crawl completes
            dispatch(new GenerateRecommendationsJob($this->crawlSession->project));

        } catch (\Exception $e) {
            Log::error("Crawl job failed for session {$this->crawlSession->id}: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        $this->crawlSession->update([
            'status' => 'failed',
            'completed_at' => now(),
        ]);

        Log::error("Crawl job failed for session {$this->crawlSession->id}", [
            'exception' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
        ]);
    }
}
