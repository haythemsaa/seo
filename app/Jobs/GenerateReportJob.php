<?php

namespace App\Jobs;

use App\Models\Report;
use App\Services\Reports\PdfReportGenerator;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GenerateReportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 600;
    protected Report $report;

    /**
     * Create a new job instance.
     */
    public function __construct(Report $report)
    {
        $this->report = $report;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info("Generating PDF report {$this->report->id}");

        try {
            $generator = new PdfReportGenerator();
            $pdfPath = $generator->generate($this->report);

            Log::info("Generated PDF report: {$pdfPath}");

            // TODO: Send email notification if scheduled
            if ($this->report->scheduled) {
                // dispatch(new SendReportEmailJob($this->report));
            }

        } catch (\Exception $e) {
            Log::error("Failed to generate report {$this->report->id}: " . $e->getMessage());
        }
    }
}
