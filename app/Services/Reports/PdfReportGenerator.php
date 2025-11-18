<?php

namespace App\Services\Reports;

use App\Models\Project;
use App\Models\Report;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class PdfReportGenerator
{
    /**
     * Generate a PDF report for a project.
     */
    public function generate(Report $report): string
    {
        $project = $report->project;
        $organization = $report->organization;

        // Collect data for the report
        $data = $this->collectReportData($project, $report->period_start, $report->period_end);

        // Check if white label is enabled
        $whiteLabelConfig = null;
        if ($organization->white_label_enabled) {
            $whiteLabelConfig = $organization->white_label_config;
        }

        // Generate PDF
        $pdf = Pdf::loadView('reports.pdf.main', [
            'report' => $report,
            'project' => $project,
            'organization' => $organization,
            'data' => $data,
            'whiteLabelConfig' => $whiteLabelConfig,
        ]);

        // Configure PDF
        $pdf->setPaper('a4', 'portrait');

        // Generate filename
        $filename = sprintf(
            'reports/%s_%s_%s.pdf',
            $project->id,
            $report->period_start->format('Y-m-d'),
            uniqid()
        );

        // Save to storage
        Storage::put($filename, $pdf->output());

        // Update report with PDF path
        $report->update(['pdf_path' => $filename]);

        return $filename;
    }

    /**
     * Collect all data needed for the report.
     */
    protected function collectReportData(Project $project, Carbon $startDate, Carbon $endDate): array
    {
        // Keywords data
        $keywords = $project->keywords()->with(['rankings' => function ($query) use ($startDate, $endDate) {
            $query->whereBetween('checked_at', [$startDate, $endDate]);
        }])->get();

        $keywordsData = [
            'total' => $keywords->count(),
            'top_3' => 0,
            'top_10' => 0,
            'top_20' => 0,
            'gainers' => [],
            'losers' => [],
        ];

        foreach ($keywords as $keyword) {
            $latest = $keyword->latestRanking;
            if ($latest) {
                if ($latest->position <= 3) $keywordsData['top_3']++;
                if ($latest->position <= 10) $keywordsData['top_10']++;
                if ($latest->position <= 20) $keywordsData['top_20']++;

                $change = $keyword->getPositionChange();
                if ($change && $change > 0) {
                    $keywordsData['gainers'][] = [
                        'keyword' => $keyword->keyword,
                        'position' => $latest->position,
                        'change' => $change,
                    ];
                } elseif ($change && $change < 0) {
                    $keywordsData['losers'][] = [
                        'keyword' => $keyword->keyword,
                        'position' => $latest->position,
                        'change' => $change,
                    ];
                }
            }
        }

        // Sort gainers and losers
        usort($keywordsData['gainers'], fn($a, $b) => $b['change'] <=> $a['change']);
        usort($keywordsData['losers'], fn($a, $b) => $a['change'] <=> $b['change']);

        // Take top 10
        $keywordsData['gainers'] = array_slice($keywordsData['gainers'], 0, 10);
        $keywordsData['losers'] = array_slice($keywordsData['losers'], 0, 10);

        // Backlinks data
        $backlinks = [
            'total' => $project->backlinks()->count(),
            'active' => $project->activeBacklinks()->count(),
            'new' => $project->backlinks()
                ->whereBetween('first_seen_at', [$startDate, $endDate])
                ->count(),
            'lost' => $project->backlinks()
                ->whereBetween('lost_at', [$startDate, $endDate])
                ->count(),
            'dofollow' => $project->backlinks()
                ->where('is_active', true)
                ->where('link_type', 'dofollow')
                ->count(),
        ];

        // Technical audit data
        $latestCrawl = $project->latestCrawl;
        $technicalData = [
            'seo_score' => $latestCrawl ? $latestCrawl->calculateSeoScore() : 0,
            'pages_crawled' => $latestCrawl ? $latestCrawl->pages_crawled : 0,
            'errors' => $latestCrawl ? $latestCrawl->errors_count : 0,
            'warnings' => $latestCrawl ? $latestCrawl->warnings_count : 0,
        ];

        // Google Search Console data
        $gscData = [];
        if ($project->google_search_console_property) {
            $gscData = \DB::table('search_console_metrics')
                ->where('project_id', $project->id)
                ->whereBetween('date', [$startDate, $endDate])
                ->select([
                    \DB::raw('SUM(clicks) as total_clicks'),
                    \DB::raw('SUM(impressions) as total_impressions'),
                    \DB::raw('AVG(ctr) as avg_ctr'),
                    \DB::raw('AVG(position) as avg_position'),
                ])
                ->first();
        }

        // AI Recommendations
        $recommendations = $project->aiRecommendations()
            ->where('status', 'pending')
            ->orderBy('priority')
            ->orderByDesc('impact_score')
            ->limit(10)
            ->get();

        return [
            'keywords' => $keywordsData,
            'backlinks' => $backlinks,
            'technical' => $technicalData,
            'gsc' => $gscData,
            'recommendations' => $recommendations,
            'visibility_score' => $project->calculateVisibilityScore(),
        ];
    }
}
