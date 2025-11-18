<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrawledPage extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'crawl_session_id',
        'project_id',
        'url',
        'url_hash',
        'status_code',
        'content_type',
        'page_size',
        'load_time',
        'title',
        'meta_description',
        'h1',
        'canonical_url',
        'robots_meta',
        'word_count',
        'internal_links_count',
        'external_links_count',
        'images_count',
        'has_https',
        'is_indexable',
        'issues',
        'content_hash',
    ];

    protected function casts(): array
    {
        return [
            'status_code' => 'integer',
            'page_size' => 'integer',
            'load_time' => 'integer',
            'word_count' => 'integer',
            'internal_links_count' => 'integer',
            'external_links_count' => 'integer',
            'images_count' => 'integer',
            'has_https' => 'boolean',
            'is_indexable' => 'boolean',
            'issues' => 'array',
            'crawled_at' => 'datetime',
        ];
    }

    /**
     * Get the crawl session that owns the page.
     */
    public function crawlSession()
    {
        return $this->belongsTo(CrawlSession::class);
    }

    /**
     * Get the project that owns the page.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Check if page has errors.
     */
    public function hasErrors(): bool
    {
        return !empty($this->issues) && count(array_filter($this->issues, fn($issue) => $issue['severity'] === 'critical')) > 0;
    }

    /**
     * Get critical issues.
     */
    public function getCriticalIssues(): array
    {
        if (empty($this->issues)) {
            return [];
        }

        return array_filter($this->issues, fn($issue) => $issue['severity'] === 'critical');
    }

    /**
     * Check if page is healthy.
     */
    public function isHealthy(): bool
    {
        return $this->status_code >= 200 &&
               $this->status_code < 300 &&
               $this->is_indexable &&
               !$this->hasErrors();
    }
}
