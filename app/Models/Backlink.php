<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Backlink extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'source_url',
        'source_domain',
        'target_url',
        'anchor_text',
        'link_type',
        'domain_authority',
        'page_authority',
        'trust_flow',
        'citation_flow',
        'is_active',
        'first_seen_at',
        'last_seen_at',
        'lost_at',
    ];

    protected function casts(): array
    {
        return [
            'domain_authority' => 'integer',
            'page_authority' => 'integer',
            'trust_flow' => 'integer',
            'citation_flow' => 'integer',
            'is_active' => 'boolean',
            'first_seen_at' => 'datetime',
            'last_seen_at' => 'datetime',
            'lost_at' => 'datetime',
        ];
    }

    /**
     * Get the project that owns the backlink.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Check if backlink is dofollow.
     */
    public function isDofollow(): bool
    {
        return $this->link_type === 'dofollow';
    }

    /**
     * Check if backlink is high quality.
     */
    public function isHighQuality(): bool
    {
        return $this->domain_authority >= 50 ||
               $this->trust_flow >= 30;
    }

    /**
     * Calculate quality score.
     */
    public function getQualityScore(): int
    {
        $score = 0;

        // Domain Authority weight: 30%
        $score += ($this->domain_authority ?? 0) * 0.3;

        // Trust Flow weight: 40%
        $score += ($this->trust_flow ?? 0) * 0.4;

        // Link type weight: 20%
        $score += $this->isDofollow() ? 20 : 10;

        // Active status weight: 10%
        $score += $this->is_active ? 10 : 0;

        return (int) min(100, round($score));
    }
}
