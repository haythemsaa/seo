<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrawlSession extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'project_id',
        'status',
        'crawl_type',
        'pages_crawled',
        'pages_total',
        'errors_count',
        'warnings_count',
        'started_at',
        'completed_at',
        'crawl_data',
    ];

    protected function casts(): array
    {
        return [
            'pages_crawled' => 'integer',
            'pages_total' => 'integer',
            'errors_count' => 'integer',
            'warnings_count' => 'integer',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
            'crawl_data' => 'array',
            'created_at' => 'datetime',
        ];
    }

    /**
     * Get the project that owns the crawl session.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get all crawled pages for this session.
     */
    public function pages()
    {
        return $this->hasMany(CrawledPage::class);
    }

    /**
     * Check if crawl is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Calculate progress percentage.
     */
    public function getProgressPercentage(): float
    {
        if ($this->pages_total === 0) {
            return 0;
        }

        return round(($this->pages_crawled / $this->pages_total) * 100, 2);
    }

    /**
     * Calculate SEO score based on issues found.
     */
    public function calculateSeoScore(): int
    {
        if (!$this->isCompleted()) {
            return 0;
        }

        $totalPages = $this->pages_crawled;
        if ($totalPages === 0) {
            return 0;
        }

        $issuesWeight = [
            'critical' => 10,
            'high' => 5,
            'medium' => 2,
            'low' => 1,
        ];

        $totalDeductions = 0;
        $totalDeductions += $this->errors_count * $issuesWeight['critical'];
        $totalDeductions += $this->warnings_count * $issuesWeight['medium'];

        $maxScore = 100;
        $score = max(0, $maxScore - ($totalDeductions / $totalPages * 10));

        return (int) round($score);
    }
}
