<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'name',
        'website_url',
        'main_domain',
        'country_code',
        'language_code',
        'search_engines',
        'competitors',
        'google_analytics_id',
        'google_search_console_property',
        'is_active',
        'crawl_frequency',
        'last_crawled_at',
        'settings',
    ];

    protected function casts(): array
    {
        return [
            'search_engines' => 'array',
            'competitors' => 'array',
            'is_active' => 'boolean',
            'last_crawled_at' => 'datetime',
            'settings' => 'array',
        ];
    }

    /**
     * Get the organization that owns the project.
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Get all keywords for the project.
     */
    public function keywords()
    {
        return $this->hasMany(Keyword::class);
    }

    /**
     * Get active keywords only.
     */
    public function activeKeywords()
    {
        return $this->keywords()->where('is_active', true);
    }

    /**
     * Get all crawl sessions for the project.
     */
    public function crawlSessions()
    {
        return $this->hasMany(CrawlSession::class);
    }

    /**
     * Get the latest crawl session.
     */
    public function latestCrawl()
    {
        return $this->hasOne(CrawlSession::class)->latestOfMany();
    }

    /**
     * Get all backlinks for the project.
     */
    public function backlinks()
    {
        return $this->hasMany(Backlink::class);
    }

    /**
     * Get active backlinks only.
     */
    public function activeBacklinks()
    {
        return $this->backlinks()->where('is_active', true);
    }

    /**
     * Get all reports for the project.
     */
    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    /**
     * Get AI recommendations for the project.
     */
    public function aiRecommendations()
    {
        return $this->hasMany(AiRecommendation::class);
    }

    /**
     * Get pending AI recommendations.
     */
    public function pendingRecommendations()
    {
        return $this->aiRecommendations()->where('status', 'pending');
    }

    /**
     * Calculate SEO visibility score.
     */
    public function calculateVisibilityScore(): float
    {
        $keywords = $this->activeKeywords()->with('latestRanking')->get();

        if ($keywords->isEmpty()) {
            return 0;
        }

        $ctrByPosition = [
            1 => 0.317, 2 => 0.247, 3 => 0.187, 4 => 0.136, 5 => 0.095,
            6 => 0.06, 7 => 0.05, 8 => 0.04, 9 => 0.035, 10 => 0.03,
        ];

        $totalVisits = 0;
        $totalVolume = 0;

        foreach ($keywords as $keyword) {
            $ranking = $keyword->latestRanking;
            $volume = $keyword->search_volume ?? 0;
            $totalVolume += $volume;

            if ($ranking && $ranking->position && $ranking->position <= 20) {
                $ctr = $ctrByPosition[$ranking->position] ?? ($ranking->position <= 20 ? 0.015 : 0.005);
                $totalVisits += $volume * $ctr;
            }
        }

        return $totalVolume > 0 ? round(($totalVisits / $totalVolume) * 100, 2) : 0;
    }
}
