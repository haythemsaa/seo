<?php

namespace App\Services\Crawler;

use App\Models\CrawlSession;
use App\Models\CrawledPage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WebCrawler
{
    protected CrawlSession $session;
    protected array $visitedUrls = [];
    protected array $queue = [];
    protected int $maxDepth;
    protected int $maxPages;
    protected string $baseUrl;
    protected string $baseDomain;

    public function __construct(CrawlSession $session)
    {
        $this->session = $session;
        $this->maxDepth = $session->project->settings['max_crawl_depth'] ?? 10;
        $this->maxPages = $session->pages_total ?? 10000;
        $this->baseUrl = $session->project->website_url;
        $this->baseDomain = parse_url($this->baseUrl, PHP_URL_HOST);
    }

    /**
     * Start the crawling process.
     */
    public function crawl(): void
    {
        $this->session->update([
            'status' => 'running',
            'started_at' => now(),
        ]);

        try {
            $this->queue[] = [
                'url' => $this->baseUrl,
                'depth' => 0,
            ];

            while (!empty($this->queue) && count($this->visitedUrls) < $this->maxPages) {
                $item = array_shift($this->queue);
                $this->crawlUrl($item['url'], $item['depth']);

                // Update progress
                $this->session->update([
                    'pages_crawled' => count($this->visitedUrls),
                ]);
            }

            $this->session->update([
                'status' => 'completed',
                'completed_at' => now(),
                'pages_crawled' => count($this->visitedUrls),
            ]);

        } catch (\Exception $e) {
            Log::error('Crawl failed: ' . $e->getMessage());

            $this->session->update([
                'status' => 'failed',
                'completed_at' => now(),
            ]);
        }
    }

    /**
     * Crawl a single URL.
     */
    protected function crawlUrl(string $url, int $depth): void
    {
        $urlHash = md5($url);

        // Skip if already visited
        if (in_array($urlHash, $this->visitedUrls)) {
            return;
        }

        $this->visitedUrls[] = $urlHash;

        try {
            $startTime = microtime(true);

            $response = Http::timeout(30)
                ->withHeaders([
                    'User-Agent' => config('crawler.user_agent', 'SEOMasterProBot/1.0'),
                ])
                ->get($url);

            $loadTime = (microtime(true) - $startTime) * 1000;

            $statusCode = $response->status();
            $contentType = $response->header('Content-Type');
            $body = $response->body();

            // Parse HTML content
            $pageData = $this->parseHtml($body, $url);

            // Save crawled page
            $crawledPage = CrawledPage::create([
                'crawl_session_id' => $this->session->id,
                'project_id' => $this->session->project_id,
                'url' => $url,
                'url_hash' => $urlHash,
                'status_code' => $statusCode,
                'content_type' => $contentType,
                'page_size' => strlen($body),
                'load_time' => (int) $loadTime,
                'title' => $pageData['title'] ?? null,
                'meta_description' => $pageData['meta_description'] ?? null,
                'h1' => $pageData['h1'] ?? null,
                'canonical_url' => $pageData['canonical_url'] ?? null,
                'robots_meta' => $pageData['robots_meta'] ?? null,
                'word_count' => $pageData['word_count'] ?? 0,
                'internal_links_count' => $pageData['internal_links_count'] ?? 0,
                'external_links_count' => $pageData['external_links_count'] ?? 0,
                'images_count' => $pageData['images_count'] ?? 0,
                'has_https' => str_starts_with($url, 'https'),
                'is_indexable' => $this->isIndexable($pageData),
                'issues' => $this->detectIssues($pageData, $statusCode),
                'content_hash' => md5($body),
            ]);

            // Count issues
            $issues = $crawledPage->issues ?? [];
            $criticalCount = count(array_filter($issues, fn($i) => $i['severity'] === 'critical'));
            $warningCount = count(array_filter($issues, fn($i) => $i['severity'] === 'medium'));

            $this->session->increment('errors_count', $criticalCount);
            $this->session->increment('warnings_count', $warningCount);

            // Add links to queue if depth allows
            if ($depth < $this->maxDepth && !empty($pageData['links'])) {
                foreach ($pageData['links'] as $link) {
                    if ($this->shouldCrawl($link)) {
                        $this->queue[] = [
                            'url' => $link,
                            'depth' => $depth + 1,
                        ];
                    }
                }
            }

        } catch (\Exception $e) {
            Log::error("Failed to crawl {$url}: " . $e->getMessage());

            // Save error page
            CrawledPage::create([
                'crawl_session_id' => $this->session->id,
                'project_id' => $this->session->project_id,
                'url' => $url,
                'url_hash' => $urlHash,
                'status_code' => 0,
                'issues' => [
                    [
                        'type' => 'crawl_error',
                        'severity' => 'critical',
                        'message' => $e->getMessage(),
                    ],
                ],
            ]);

            $this->session->increment('errors_count');
        }
    }

    /**
     * Parse HTML content.
     */
    protected function parseHtml(string $html, string $url): array
    {
        $dom = new \DOMDocument();
        @$dom->loadHTML($html);

        $data = [
            'links' => [],
            'internal_links_count' => 0,
            'external_links_count' => 0,
            'images_count' => 0,
        ];

        // Extract title
        $titles = $dom->getElementsByTagName('title');
        if ($titles->length > 0) {
            $data['title'] = trim($titles->item(0)->textContent);
        }

        // Extract meta description
        $metas = $dom->getElementsByTagName('meta');
        foreach ($metas as $meta) {
            if ($meta->getAttribute('name') === 'description') {
                $data['meta_description'] = $meta->getAttribute('content');
            }
            if ($meta->getAttribute('name') === 'robots') {
                $data['robots_meta'] = $meta->getAttribute('content');
            }
        }

        // Extract H1
        $h1s = $dom->getElementsByTagName('h1');
        if ($h1s->length > 0) {
            $data['h1'] = trim($h1s->item(0)->textContent);
        }

        // Extract canonical
        $links = $dom->getElementsByTagName('link');
        foreach ($links as $link) {
            if ($link->getAttribute('rel') === 'canonical') {
                $data['canonical_url'] = $link->getAttribute('href');
            }
        }

        // Extract links
        $anchors = $dom->getElementsByTagName('a');
        foreach ($anchors as $anchor) {
            $href = $anchor->getAttribute('href');
            if (empty($href) || str_starts_with($href, '#') || str_starts_with($href, 'javascript:')) {
                continue;
            }

            $absoluteUrl = $this->makeAbsoluteUrl($href, $url);
            $data['links'][] = $absoluteUrl;

            if ($this->isInternalUrl($absoluteUrl)) {
                $data['internal_links_count']++;
            } else {
                $data['external_links_count']++;
            }
        }

        // Count images
        $images = $dom->getElementsByTagName('img');
        $data['images_count'] = $images->length;

        // Count words
        $text = strip_tags($html);
        $words = str_word_count($text);
        $data['word_count'] = $words;

        return $data;
    }

    /**
     * Check if page is indexable.
     */
    protected function isIndexable(array $pageData): bool
    {
        $robotsMeta = $pageData['robots_meta'] ?? '';

        if (str_contains(strtolower($robotsMeta), 'noindex')) {
            return false;
        }

        return true;
    }

    /**
     * Detect SEO issues.
     */
    protected function detectIssues(array $pageData, int $statusCode): array
    {
        $issues = [];

        // Status code issues
        if ($statusCode >= 400) {
            $issues[] = [
                'type' => 'http_error',
                'severity' => 'critical',
                'message' => "HTTP {$statusCode} error",
            ];
        }

        // Title issues
        if (empty($pageData['title'])) {
            $issues[] = [
                'type' => 'missing_title',
                'severity' => 'critical',
                'message' => 'Missing title tag',
            ];
        } elseif (strlen($pageData['title']) > 60) {
            $issues[] = [
                'type' => 'title_too_long',
                'severity' => 'medium',
                'message' => 'Title tag is too long (>60 characters)',
            ];
        }

        // Meta description issues
        if (empty($pageData['meta_description'])) {
            $issues[] = [
                'type' => 'missing_meta_description',
                'severity' => 'high',
                'message' => 'Missing meta description',
            ];
        }

        // H1 issues
        if (empty($pageData['h1'])) {
            $issues[] = [
                'type' => 'missing_h1',
                'severity' => 'high',
                'message' => 'Missing H1 tag',
            ];
        }

        // Content issues
        if (($pageData['word_count'] ?? 0) < 300) {
            $issues[] = [
                'type' => 'thin_content',
                'severity' => 'medium',
                'message' => 'Thin content (less than 300 words)',
            ];
        }

        return $issues;
    }

    /**
     * Check if URL should be crawled.
     */
    protected function shouldCrawl(string $url): bool
    {
        if (!$this->isInternalUrl($url)) {
            return false;
        }

        $urlHash = md5($url);
        if (in_array($urlHash, $this->visitedUrls)) {
            return false;
        }

        // Skip common non-HTML extensions
        $skipExtensions = ['.pdf', '.jpg', '.jpeg', '.png', '.gif', '.css', '.js', '.xml'];
        foreach ($skipExtensions as $ext) {
            if (str_ends_with(strtolower($url), $ext)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check if URL is internal.
     */
    protected function isInternalUrl(string $url): bool
    {
        $domain = parse_url($url, PHP_URL_HOST);
        return $domain === $this->baseDomain;
    }

    /**
     * Make URL absolute.
     */
    protected function makeAbsoluteUrl(string $url, string $baseUrl): string
    {
        if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://')) {
            return $url;
        }

        $parsedBase = parse_url($baseUrl);
        $scheme = $parsedBase['scheme'];
        $host = $parsedBase['host'];

        if (str_starts_with($url, '//')) {
            return $scheme . ':' . $url;
        }

        if (str_starts_with($url, '/')) {
            return $scheme . '://' . $host . $url;
        }

        $path = $parsedBase['path'] ?? '/';
        $path = dirname($path);

        return $scheme . '://' . $host . $path . '/' . $url;
    }
}
