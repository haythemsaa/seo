<?php

namespace App\Services\SEO;

use App\Models\Keyword;
use App\Models\KeywordRanking;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RankTrackerService
{
    /**
     * Check ranking for a keyword.
     */
    public function checkRanking(Keyword $keyword, string $device = 'desktop', ?string $location = null): ?KeywordRanking
    {
        try {
            $project = $keyword->project;
            $searchEngine = 'google';

            // Get SERP results (using a SERP API like ValueSERP, SerpApi, etc.)
            $results = $this->fetchSerpResults(
                $keyword->keyword,
                $project->country_code,
                $device,
                $location
            );

            $position = null;
            $url = null;
            $featuredSnippet = false;
            $localPack = false;
            $serpFeatures = [];

            // Find our domain in results
            foreach ($results['organic'] ?? [] as $index => $result) {
                if ($this->urlBelongsToProject($result['link'], $project->main_domain)) {
                    $position = $index + 1;
                    $url = $result['link'];
                    break;
                }
            }

            // Check for featured snippet
            if (isset($results['featured_snippet'])) {
                if ($this->urlBelongsToProject($results['featured_snippet']['link'] ?? '', $project->main_domain)) {
                    $featuredSnippet = true;
                }
            }

            // Check for local pack
            if (isset($results['local_pack'])) {
                foreach ($results['local_pack'] as $local) {
                    if ($this->urlBelongsToProject($local['link'] ?? '', $project->main_domain)) {
                        $localPack = true;
                        break;
                    }
                }
            }

            // Record SERP features
            if (isset($results['knowledge_graph'])) {
                $serpFeatures[] = 'knowledge_graph';
            }
            if (isset($results['featured_snippet'])) {
                $serpFeatures[] = 'featured_snippet';
            }
            if (isset($results['local_pack'])) {
                $serpFeatures[] = 'local_pack';
            }
            if (isset($results['people_also_ask'])) {
                $serpFeatures[] = 'people_also_ask';
            }

            // Create ranking record
            $ranking = KeywordRanking::create([
                'keyword_id' => $keyword->id,
                'project_id' => $project->id,
                'search_engine' => $searchEngine,
                'device_type' => $device,
                'location' => $location,
                'position' => $position,
                'url' => $url,
                'featured_snippet' => $featuredSnippet,
                'local_pack' => $localPack,
                'serp_features' => $serpFeatures,
            ]);

            Log::info("Checked ranking for keyword {$keyword->id}: position {$position}");

            return $ranking;

        } catch (\Exception $e) {
            Log::error("Failed to check ranking for keyword {$keyword->id}: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Check rankings for all keywords in a project.
     */
    public function checkProjectRankings($project): int
    {
        $keywords = $project->activeKeywords()->get();
        $checked = 0;

        foreach ($keywords as $keyword) {
            $this->checkRanking($keyword);
            $checked++;

            // Rate limiting: wait between requests
            sleep(2);
        }

        return $checked;
    }

    /**
     * Fetch SERP results from API.
     */
    protected function fetchSerpResults(string $query, string $country, string $device, ?string $location): array
    {
        // Example using a mock SERP API
        // In production, integrate with ValueSERP, SerpApi, or similar

        // For now, return mock data
        return $this->getMockSerpData($query);
    }

    /**
     * Check if URL belongs to project domain.
     */
    protected function urlBelongsToProject(string $url, string $domain): bool
    {
        $urlHost = parse_url($url, PHP_URL_HOST);
        return $urlHost === $domain || str_ends_with($urlHost, '.' . $domain);
    }

    /**
     * Get mock SERP data for development.
     */
    protected function getMockSerpData(string $query): array
    {
        return [
            'organic' => [
                ['link' => 'https://example1.com/page1', 'title' => 'Result 1'],
                ['link' => 'https://example2.com/page2', 'title' => 'Result 2'],
                ['link' => 'https://example3.com/page3', 'title' => 'Result 3'],
                ['link' => 'https://yoursite.com/page', 'title' => 'Your Page'],
                ['link' => 'https://example5.com/page5', 'title' => 'Result 5'],
            ],
            'featured_snippet' => [
                'link' => 'https://competitor.com/snippet',
                'title' => 'Featured Snippet',
            ],
        ];
    }
}
