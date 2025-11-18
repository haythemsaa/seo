<?php

namespace App\Services\SEO;

use App\Models\Project;
use App\Models\SearchConsoleMetric;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class GoogleSearchConsoleService
{
    protected string $apiUrl = 'https://searchconsole.googleapis.com/v1';
    protected ?string $accessToken = null;

    public function __construct(?string $accessToken = null)
    {
        $this->accessToken = $accessToken;
    }

    /**
     * Get OAuth authorization URL.
     */
    public static function getAuthUrl(): string
    {
        $params = http_build_query([
            'client_id' => config('services.google.client_id'),
            'redirect_uri' => config('services.google.redirect_uri'),
            'response_type' => 'code',
            'scope' => 'https://www.googleapis.com/auth/webmasters.readonly',
            'access_type' => 'offline',
            'prompt' => 'consent',
        ]);

        return 'https://accounts.google.com/o/oauth2/v2/auth?' . $params;
    }

    /**
     * Exchange authorization code for access token.
     */
    public static function exchangeCodeForToken(string $code): array
    {
        $response = Http::post('https://oauth2.googleapis.com/token', [
            'client_id' => config('services.google.client_id'),
            'client_secret' => config('services.google.client_secret'),
            'redirect_uri' => config('services.google.redirect_uri'),
            'grant_type' => 'authorization_code',
            'code' => $code,
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('Failed to exchange code for token: ' . $response->body());
    }

    /**
     * Import Search Console data for a project.
     */
    public function importData(Project $project, Carbon $startDate, Carbon $endDate): int
    {
        if (!$project->google_search_console_property) {
            throw new \Exception('Project does not have a Search Console property configured');
        }

        $property = $project->google_search_console_property;
        $imported = 0;

        try {
            $response = Http::withToken($this->accessToken)
                ->post("{$this->apiUrl}/webmasters/v3/sites/{$property}/searchAnalytics/query", [
                    'startDate' => $startDate->format('Y-m-d'),
                    'endDate' => $endDate->format('Y-m-d'),
                    'dimensions' => ['query', 'page', 'date'],
                    'rowLimit' => 25000,
                ]);

            if (!$response->successful()) {
                throw new \Exception('GSC API error: ' . $response->body());
            }

            $data = $response->json();
            $rows = $data['rows'] ?? [];

            foreach ($rows as $row) {
                SearchConsoleMetric::updateOrCreate(
                    [
                        'project_id' => $project->id,
                        'query' => $row['keys'][0],
                        'page' => $row['keys'][1],
                        'date' => $row['keys'][2],
                    ],
                    [
                        'clicks' => $row['clicks'],
                        'impressions' => $row['impressions'],
                        'ctr' => $row['ctr'],
                        'position' => $row['position'],
                    ]
                );

                $imported++;
            }

            Log::info("Imported {$imported} GSC metrics for project {$project->id}");

            return $imported;

        } catch (\Exception $e) {
            Log::error('GSC import failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get sites from Search Console.
     */
    public function getSites(): array
    {
        $response = Http::withToken($this->accessToken)
            ->get("{$this->apiUrl}/webmasters/v3/sites");

        if ($response->successful()) {
            return $response->json()['siteEntry'] ?? [];
        }

        throw new \Exception('Failed to fetch sites: ' . $response->body());
    }

    /**
     * Sync keywords with GSC queries.
     */
    public function syncKeywords(Project $project): int
    {
        $startDate = now()->subDays(90);
        $endDate = now();

        $response = Http::withToken($this->accessToken)
            ->post("{$this->apiUrl}/webmasters/v3/sites/{$project->google_search_console_property}/searchAnalytics/query", [
                'startDate' => $startDate->format('Y-m-d'),
                'endDate' => $endDate->format('Y-m-d'),
                'dimensions' => ['query'],
                'rowLimit' => 1000,
            ]);

        if (!$response->successful()) {
            throw new \Exception('GSC API error: ' . $response->body());
        }

        $data = $response->json();
        $rows = $data['rows'] ?? [];
        $synced = 0;

        foreach ($rows as $row) {
            $query = $row['keys'][0];

            // Create keyword if it doesn't exist
            $keyword = $project->keywords()->firstOrCreate(
                ['keyword' => $query],
                [
                    'search_volume' => $row['impressions'] ?? 0,
                    'is_active' => true,
                ]
            );

            $synced++;
        }

        return $synced;
    }
}
