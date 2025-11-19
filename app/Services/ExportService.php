<?php

namespace App\Services;

use App\Models\Project;
use App\Models\Keyword;
use App\Models\Backlink;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Response;
use Barryvdh\DomPDF\Facade\Pdf;

class ExportService
{
    /**
     * Export keywords to CSV
     */
    public function exportKeywordsToCsv(Project $project): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $keywords = $project->keywords()->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="keywords_' . $project->id . '_' . date('Y-m-d') . '.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() use ($keywords) {
            $file = fopen('php://output', 'w');

            // UTF-8 BOM for Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Headers
            fputcsv($file, [
                'Mot-clé',
                'URL Cible',
                'Position Actuelle',
                'Position Précédente',
                'Évolution',
                'Meilleure Position',
                'Pire Position',
                'Volume de Recherche',
                'Moteur',
                'Pays',
                'Langue',
                'Device',
                'Dernière Vérification',
            ], ';');

            // Data
            foreach ($keywords as $keyword) {
                $evolution = $keyword->previous_position
                    ? ($keyword->previous_position - $keyword->current_position)
                    : 0;

                fputcsv($file, [
                    $keyword->keyword,
                    $keyword->target_url,
                    $keyword->current_position ?? 'N/A',
                    $keyword->previous_position ?? 'N/A',
                    $evolution > 0 ? '+' . $evolution : $evolution,
                    $keyword->best_position ?? 'N/A',
                    $keyword->worst_position ?? 'N/A',
                    $keyword->search_volume ?? 'N/A',
                    $keyword->search_engine,
                    $keyword->country,
                    $keyword->language,
                    $keyword->device,
                    $keyword->last_check ? $keyword->last_check->format('d/m/Y H:i') : 'Jamais',
                ], ';');
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    /**
     * Export backlinks to CSV
     */
    public function exportBacklinksToCsv(Project $project): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $backlinks = $project->backlinks()->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="backlinks_' . $project->id . '_' . date('Y-m-d') . '.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() use ($backlinks) {
            $file = fopen('php://output', 'w');

            // UTF-8 BOM
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Headers
            fputcsv($file, [
                'URL Source',
                'URL Cible',
                'Ancre',
                'Attribut Rel',
                'Statut',
                'Domain Authority',
                'Page Authority',
                'Spam Score',
                'Premier Vu',
                'Dernière Vérification',
            ], ';');

            // Data
            foreach ($backlinks as $backlink) {
                fputcsv($file, [
                    $backlink->source_url,
                    $backlink->target_url,
                    $backlink->anchor_text,
                    $backlink->rel_attribute,
                    $backlink->status,
                    $backlink->domain_authority ?? 'N/A',
                    $backlink->page_authority ?? 'N/A',
                    $backlink->spam_score ?? 'N/A',
                    $backlink->first_seen ? $backlink->first_seen->format('d/m/Y') : 'N/A',
                    $backlink->last_checked ? $backlink->last_checked->format('d/m/Y H:i') : 'Jamais',
                ], ';');
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    /**
     * Export project report to PDF
     */
    public function exportProjectReportToPdf(Project $project): \Illuminate\Http\Response
    {
        $data = [
            'project' => $project,
            'keywords' => $project->keywords()->get(),
            'backlinks' => $project->backlinks()->get(),
            'statistics' => $this->calculateProjectStatistics($project),
            'generatedAt' => now()->format('d/m/Y H:i'),
        ];

        $pdf = Pdf::loadView('exports.project-report', $data)
            ->setPaper('a4')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'DejaVu Sans',
            ]);

        return $pdf->download('rapport_' . $project->id . '_' . date('Y-m-d') . '.pdf');
    }

    /**
     * Export keywords to Excel (using CSV format)
     */
    public function exportKeywordsToExcel(Project $project): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $keywords = $project->keywords()->get();

        $headers = [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="keywords_' . $project->id . '_' . date('Y-m-d') . '.xls"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() use ($keywords, $project) {
            echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
            echo '<?mso-application progid="Excel.Sheet"?>' . "\n";
            echo '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"' . "\n";
            echo '    xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet">' . "\n";
            echo '  <Worksheet ss:Name="Keywords">' . "\n";
            echo '    <Table>' . "\n";

            // Header row
            echo '      <Row>' . "\n";
            $headers = ['Mot-clé', 'URL Cible', 'Position Actuelle', 'Position Précédente', 'Évolution',
                       'Meilleure Position', 'Pire Position', 'Volume de Recherche', 'Dernière Vérification'];
            foreach ($headers as $header) {
                echo '        <Cell><Data ss:Type="String">' . htmlspecialchars($header) . '</Data></Cell>' . "\n";
            }
            echo '      </Row>' . "\n";

            // Data rows
            foreach ($keywords as $keyword) {
                $evolution = $keyword->previous_position
                    ? ($keyword->previous_position - $keyword->current_position)
                    : 0;

                echo '      <Row>' . "\n";
                echo '        <Cell><Data ss:Type="String">' . htmlspecialchars($keyword->keyword) . '</Data></Cell>' . "\n";
                echo '        <Cell><Data ss:Type="String">' . htmlspecialchars($keyword->target_url) . '</Data></Cell>' . "\n";
                echo '        <Cell><Data ss:Type="Number">' . ($keyword->current_position ?? 0) . '</Data></Cell>' . "\n";
                echo '        <Cell><Data ss:Type="Number">' . ($keyword->previous_position ?? 0) . '</Data></Cell>' . "\n";
                echo '        <Cell><Data ss:Type="Number">' . $evolution . '</Data></Cell>' . "\n";
                echo '        <Cell><Data ss:Type="Number">' . ($keyword->best_position ?? 0) . '</Data></Cell>' . "\n";
                echo '        <Cell><Data ss:Type="Number">' . ($keyword->worst_position ?? 0) . '</Data></Cell>' . "\n";
                echo '        <Cell><Data ss:Type="Number">' . ($keyword->search_volume ?? 0) . '</Data></Cell>' . "\n";
                echo '        <Cell><Data ss:Type="String">' . ($keyword->last_check ? $keyword->last_check->format('d/m/Y H:i') : 'Jamais') . '</Data></Cell>' . "\n";
                echo '      </Row>' . "\n";
            }

            echo '    </Table>' . "\n";
            echo '  </Worksheet>' . "\n";
            echo '</Workbook>' . "\n";
        };

        return Response::stream($callback, 200, $headers);
    }

    /**
     * Export backlinks to Excel (using CSV format)
     */
    public function exportBacklinksToExcel(Project $project): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $backlinks = $project->backlinks()->get();

        $headers = [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="backlinks_' . $project->id . '_' . date('Y-m-d') . '.xls"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() use ($backlinks) {
            echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
            echo '<?mso-application progid="Excel.Sheet"?>' . "\n";
            echo '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"' . "\n";
            echo '    xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet">' . "\n";
            echo '  <Worksheet ss:Name="Backlinks">' . "\n";
            echo '    <Table>' . "\n";

            // Header row
            echo '      <Row>' . "\n";
            $headers = ['URL Source', 'URL Cible', 'Ancre', 'Attribut Rel', 'Statut',
                       'Domain Authority', 'Page Authority', 'Spam Score', 'Premier Vu'];
            foreach ($headers as $header) {
                echo '        <Cell><Data ss:Type="String">' . htmlspecialchars($header) . '</Data></Cell>' . "\n";
            }
            echo '      </Row>' . "\n";

            // Data rows
            foreach ($backlinks as $backlink) {
                echo '      <Row>' . "\n";
                echo '        <Cell><Data ss:Type="String">' . htmlspecialchars($backlink->source_url) . '</Data></Cell>' . "\n";
                echo '        <Cell><Data ss:Type="String">' . htmlspecialchars($backlink->target_url) . '</Data></Cell>' . "\n";
                echo '        <Cell><Data ss:Type="String">' . htmlspecialchars($backlink->anchor_text) . '</Data></Cell>' . "\n";
                echo '        <Cell><Data ss:Type="String">' . htmlspecialchars($backlink->rel_attribute) . '</Data></Cell>' . "\n";
                echo '        <Cell><Data ss:Type="String">' . htmlspecialchars($backlink->status) . '</Data></Cell>' . "\n";
                echo '        <Cell><Data ss:Type="Number">' . ($backlink->domain_authority ?? 0) . '</Data></Cell>' . "\n";
                echo '        <Cell><Data ss:Type="Number">' . ($backlink->page_authority ?? 0) . '</Data></Cell>' . "\n";
                echo '        <Cell><Data ss:Type="Number">' . ($backlink->spam_score ?? 0) . '</Data></Cell>' . "\n";
                echo '        <Cell><Data ss:Type="String">' . ($backlink->first_seen ? $backlink->first_seen->format('d/m/Y') : 'N/A') . '</Data></Cell>' . "\n";
                echo '      </Row>' . "\n";
            }

            echo '    </Table>' . "\n";
            echo '  </Worksheet>' . "\n";
            echo '</Workbook>' . "\n";
        };

        return Response::stream($callback, 200, $headers);
    }

    /**
     * Calculate project statistics
     */
    private function calculateProjectStatistics(Project $project): array
    {
        $keywords = $project->keywords;
        $backlinks = $project->backlinks;

        $topTenKeywords = $keywords->filter(function($keyword) {
            return $keyword->current_position && $keyword->current_position <= 10;
        })->count();

        $improvedKeywords = $keywords->filter(function($keyword) {
            return $keyword->current_position && $keyword->previous_position
                && $keyword->current_position < $keyword->previous_position;
        })->count();

        $declinedKeywords = $keywords->filter(function($keyword) {
            return $keyword->current_position && $keyword->previous_position
                && $keyword->current_position > $keyword->previous_position;
        })->count();

        $activeBacklinks = $backlinks->where('status', 'active')->count();
        $lostBacklinks = $backlinks->where('status', 'lost')->count();

        $avgDomainAuthority = $backlinks->where('status', 'active')->avg('domain_authority');
        $avgSpamScore = $backlinks->where('status', 'active')->avg('spam_score');

        return [
            'total_keywords' => $keywords->count(),
            'top_ten_keywords' => $topTenKeywords,
            'improved_keywords' => $improvedKeywords,
            'declined_keywords' => $declinedKeywords,
            'stable_keywords' => $keywords->count() - $improvedKeywords - $declinedKeywords,
            'total_backlinks' => $backlinks->count(),
            'active_backlinks' => $activeBacklinks,
            'lost_backlinks' => $lostBacklinks,
            'avg_domain_authority' => round($avgDomainAuthority ?? 0, 1),
            'avg_spam_score' => round($avgSpamScore ?? 0, 1),
            'dofollow_backlinks' => $backlinks->where('rel_attribute', 'dofollow')->where('status', 'active')->count(),
            'nofollow_backlinks' => $backlinks->where('rel_attribute', 'nofollow')->where('status', 'active')->count(),
        ];
    }

    /**
     * Export analytics data to JSON
     */
    public function exportAnalyticsToJson(Project $project): \Illuminate\Http\JsonResponse
    {
        $data = [
            'project' => [
                'id' => $project->id,
                'name' => $project->name,
                'url' => $project->url,
                'created_at' => $project->created_at->toIso8601String(),
            ],
            'statistics' => $this->calculateProjectStatistics($project),
            'keywords' => $project->keywords()->get()->map(function($keyword) {
                return [
                    'keyword' => $keyword->keyword,
                    'current_position' => $keyword->current_position,
                    'previous_position' => $keyword->previous_position,
                    'search_volume' => $keyword->search_volume,
                    'last_check' => $keyword->last_check?->toIso8601String(),
                ];
            }),
            'backlinks' => $project->backlinks()->where('status', 'active')->get()->map(function($backlink) {
                return [
                    'source_url' => $backlink->source_url,
                    'target_url' => $backlink->target_url,
                    'domain_authority' => $backlink->domain_authority,
                    'page_authority' => $backlink->page_authority,
                    'spam_score' => $backlink->spam_score,
                ];
            }),
            'exported_at' => now()->toIso8601String(),
        ];

        return response()->json($data, 200, [
            'Content-Disposition' => 'attachment; filename="analytics_' . $project->id . '_' . date('Y-m-d') . '.json"'
        ]);
    }
}
